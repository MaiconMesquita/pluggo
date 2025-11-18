
import 'package:flutter/cupertino.dart';
import 'package:intl/intl.dart';
import 'package:pluggo/src/infra/dto/users_dto.dart';

import '../../../utils/formatters/formatter.dart';
import '../../../utils/warning_messages.dart';
import '../../domain/entity/spot.dart';
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
  }
  final CustomLoadingButtonController btnController = CustomLoadingButtonController();
  final nameController = TextEditingController();
  final userNameController = TextEditingController();

  final productValue = TextEditingController();
  final estimateController = TextEditingController();
  final lastPurchaseController = TextEditingController();
  List<Spot> mySpots = [];

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

  Future<void> loadMySpots() async {
    setLoading(true);

    try {
      String? token = await secureStorageRepository.getItem('accessToken');

      final response = await dinamicRepository.getRequest(
        url: ApiEndpoints.urlListSpots,
        popups: {},
        authorization: 'Bearer $token',
      );

      if (response?.statusCode == 200 && response?.data != null) {
        // Converte List<dynamic> para List<Spot>
        mySpots = (response!.data as List<dynamic>)
            .map((json) => Spot.fromJson(json as Map<String, dynamic>))
            .toList();

        print("SPOTS CARREGADOS: $mySpots");
      }
    } catch (e) {
      print("Erro ao carregar spots: $e");
    }

    setLoading(false);
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