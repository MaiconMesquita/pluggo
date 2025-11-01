
import 'package:flutter/cupertino.dart';
import 'package:intl/intl.dart';
import 'package:pluggo/src/infra/dto/users_dto.dart';

import '../../../utils/formatters/formatter.dart';
import '../../../utils/warning_messages.dart';
import '../../infra/dto/create_product_dto.dart';
import '../../infra/dto/event_dto.dart';
import '../../infra/dto/google_event_dto.dart';
import '../../infra/dto/product_dto.dart';
import '../../infra/dto/product_global_dto.dart';
import '../../infra/repository/api_endpoints.dart';
import '../../infra/repository/dinamic_repository.dart';
import '../../infra/repository/secure_storage_repository.dart';

import '../../provider/provider.dart';
import '../../provider/user_provider.dart';
import '../widgets/button/custom_rounded_loading_button.dart';
import '../widgets/pop_up/popUp_config.dart';


class MainProvider extends CustomChangeNotifier {
  UserProvider userProvider;
  GoogleEventProvider googleEventProvider;
  final SecureStorageRepository secureStorageRepository; //pra armazenar os dados de registro
  final DinamicRepository dinamicRepository;
  final ProductProvider productProvider;
  final ProductGlobalProvider productGlobalProvider;
  final UsersProvider usersProvider;

  MainProvider(
      this.userProvider,
      this.googleEventProvider,
      this.secureStorageRepository,
      this.dinamicRepository,
      this.productProvider,
      this.productGlobalProvider,
      this.usersProvider
      ) {
    _getUserProfile();
  }
  final CustomLoadingButtonController btnController = CustomLoadingButtonController();
  final nameController = TextEditingController();
  final userNameController = TextEditingController();

  final productValue = TextEditingController();
  final estimateController = TextEditingController();
  final lastPurchaseController = TextEditingController();

  ProductGlobalItem? _productDetail;
  ProductGlobalItem? get productDetail => _productDetail;
  final List<String?> pages = [null];

  bool _isLoading = true;
  bool get isLoading => _isLoading;
  int? totalNotif = 0;
  int pageKey = 1;
  bool load = true;

  void setLoading(bool value) {
    _isLoading = value;
    notifyListeners();
  }

  void setProductDetail(ProductGlobalItem product) {
    _productDetail = product;
    notifyListeners();
  }

  Future<void> refresh() async {
    await refreshList();
    notifyListeners();
  }

  Future<void> refreshList() async {
    setLoading(true);
    final id = userProvider.id;
    try {
      final response = await dinamicRepository.getRequest(
          url: ApiEndpoints.urlListProducts,
          parameters: {"userId": id},
          popups: {},
          authorization: ''
      );
      print(response?.data);
      final products = ProductResponse.fromJson(response?.data);
      productProvider.setProductsFromResponse(products);
      setLoading(false);
      return;
    } catch(e, stackTrace){
      print('$e e $stackTrace');
    }
  }
  Future<void> globalList() async {
    print('veio pro global list');
    setLoading(true);
    try {
      final response = await dinamicRepository.getRequest(
        url: ApiEndpoints.urlListGlobalProducts,
        popups: {},
        authorization: '',
      );

      final data = response?.data;
      print(data);

      if (data is List) {
        // ✅ Agora a resposta é diretamente uma lista de produtos
        productGlobalProvider.setProductsFromList(data);
      } else {
        print("❌ Resposta malformada: esperava uma lista. Recebido: $data");
        productGlobalProvider.setProductsFromList([]); // ou não fazer nada
      }

    } catch (e, stackTrace) {
      print('❌ Erro ao carregar lista global: $e');
      print(stackTrace);
    } finally {
      setLoading(false);
    }
  }

  Future<void> userslList() async {
    setLoading(true);
    print('veio aqui');
    try {
      final response = await dinamicRepository.getRequest(
        url: ApiEndpoints.urlUsersList,
        popups: {},
        parameters: {'username' : userNameController.text},
        authorization: '',
      );

      final data = response?.data;
      print(data);
      final response2 = UsersResponse.fromJson(data as List<dynamic>);
      usersProvider.setUsersFromResponse(response2);
      return;
    } catch (e, stackTrace) {
      print('❌ Erro ao carregar lista global: $e');
      print(stackTrace);
    } finally {
      setLoading(false);
    }
  }


  Future<void> _getUserProfile() async {
    String? token = await secureStorageRepository.getItem('accessToken');
    setLoading(true);
    load = true;
    try {
      setLoading(false);
      load = false;
    } catch (e, stackTrace) {
      await handleError(e, stackTrace);
    }
  }

  Future<void> getCreateProduct() async {
    setLoading(true);
    final id = userProvider.id;

    final name = nameController.text;
    final value = double.tryParse(Formatter.removeNonNumbers(productValue.text)) ?? 0.0;
    final estimateWasteDays = int.tryParse(estimateController.text) ?? 0;
    DateTime? lastPurchaseDate;
    try {
      lastPurchaseDate = DateFormat('dd/MM/yyyy').parseStrict(lastPurchaseController.text);
    } catch (_) {
      lastPurchaseDate = DateTime.now(); // fallback
    }
    print(name);
    print(value);
    print(estimateWasteDays);
    print(lastPurchaseController.text);
    print(lastPurchaseDate);

    final product = NewProduct(
      userId: id,
      name: name,
      value: value,
      estimateWasteDays: estimateWasteDays,
      lastPurchaseDate: lastPurchaseDate,
      calendarEventId: '',
      globalStock: true
    );

    try {
      final response = await dinamicRepository.postRequest(
        url: ApiEndpoints.urlListProducts,
        body: product.toJson(),
        popups: {},
        authorization: '',
      );

      // Captura os dados do produto retornado
      final data = response?.data;
      if (data != null && data['reminderDate'] != null) {
        // Ex: "2025-05-10"
        final reminderDate = DateTime.parse(data['reminderDate']);

        // Adiciona hora: meio-dia no start, +1h no end
        final start = DateTime(reminderDate.year, reminderDate.month, reminderDate.day, 12, 0);
        final end = start.add(const Duration(hours: 1));

        // Usa o valor retornado, caso queira garantir usar o do backend
        final productValueReturned = double.tryParse(data['value'].toString()) ?? value;

        await createGoogleCalendarEvent(start, end, productValueReturned.toString());
      }

      print(response?.data);
      setLoading(false);
      return;
    } catch (e, stackTrace) {
      print('Erro ao criar produto: $e\n$stackTrace');
    }
  }
  Future<void> detailedProduct(int productId) async {
    setLoading(true);

    try {
      final response = await dinamicRepository.getRequest(
        url: '${ApiEndpoints.urlListProducts}/$productId',
        parameters: {},
        popups: {},
        authorization: '',
      );

      setLoading(false);

      if (response?.data is Map<String, dynamic>) {
        final json = response?.data as Map<String, dynamic>;
        final product = ProductGlobalItem.fromJson(json);
        setProductDetail(product);
      } else {
        print("❌ Resposta inesperada: ${response?.data}");
      }
    } catch (e, stackTrace) {
      print('Erro ao buscar produto detalhado: $e\n$stackTrace');
    }
  }

  Future<void> commentProduct(int productId, String text) async {
    setLoading(true);
    final id = userProvider.id;

    try {
      final response = await dinamicRepository.postRequest(
        url: '${ApiEndpoints.urlComment}/$productId',
        body: {
          "userId": id,
          "text": text
        },
        popups: {},
        authorization: '',
      );

      print(response?.data);

      setLoading(false);
      return;
    } catch (e, stackTrace) {
      print('Erro ao criar produto: $e\n$stackTrace');
    }
  }


  Future<void> followUser(String UserId) async {
    setLoading(true);
    final id = userProvider.id;
print(id);
    try {
      final response = await dinamicRepository.postRequest(
        url: ApiEndpoints.urlFollowUser,
        body: {
          "followerId": id, //usuário que vai seguir
          "followedId": UserId
        },
        popups: {},
        authorization: '',
      );

      print(response?.data);

      setLoading(false);
      return;
    } catch (e, stackTrace) {
      print('Erro ao criar produto: $e\n$stackTrace');
    }
  }

  Future<void> copyProduct(int productId) async {
    print('veio aqui');
    setLoading(true);
    final id = userProvider.id;

    try {
      final response = await dinamicRepository.postRequest(
        url: '${ApiEndpoints.urlCopyProduct}/$productId?userId=$id',
        body: {},
        popups: {},
        authorization: '',
      );

      setLoading(false);
      return;
    } catch (e, stackTrace) {
      print('Erro ao criar produto: $e\n$stackTrace');
    }
  }


  Future<void> createGoogleCalendarEvent(DateTime start, DateTime end, String value) async {
    setLoading(true);

    String? token = await secureStorageRepository.getItem('accessToken');
    final evento = GoogleCalendarEventDto(
      summary: nameController.text,
      description: 'Comprar ${nameController.text} no valor de $value',
      startDateTime: start,
      endDateTime: end,
    );

    try {
      final response = await dinamicRepository.postRequest(
        url: ApiEndpoints.urlCreateEvent,
        body: evento.toJson(),
        popups: {
          406: PopUpConfig(popUp: AlertMessages.establishmentNotActivated, success: false)
        },
        authorization: 'Bearer $token',
      );

      // Verifique se a resposta foi bem-sucedida
      if (response?.statusCode == 406) {
        return;
      }

      // Se a resposta for bem-sucedida, transforme o payload no formato de GoogleEvent
      if (response?.statusCode == 200 && response?.data != null) {
        final eventJson = response?.data; // Supondo que a resposta seja um JSON
        final googleEvent = GoogleEvent.fromJson(eventJson);
        // Atualize o estado com o novo evento
        googleEventProvider.setGoogleEvent(eventJson);
      }
      setLoading(false);

      return;
    } catch (e) {
      rethrow;
    }
  }



}