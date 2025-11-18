
import 'package:flutter/cupertino.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
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


class MapsProvider extends CustomChangeNotifier {
  UserProvider userProvider;
  final SecureStorageRepository secureStorageRepository; //pra armazenar os dados de registro
  final DinamicRepository dinamicRepository;

  MapsProvider(
      this.userProvider,
      this.secureStorageRepository,
      this.dinamicRepository,
      ) {
    spotList();
  }
  final CustomLoadingButtonController btnController = CustomLoadingButtonController();
  final nameController = TextEditingController();
  final userNameController = TextEditingController();
  List<Spot> spots = [];
  LatLng? _selectedSpot;
  LatLng? get selectedSpot => _selectedSpot;


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

  void setSelectedSpot(LatLng spot) {
    _selectedSpot = spot;
    notifyListeners();
  }

  void setProductDetail(ProductGlobalItem product) {
    _productDetail = product;
    notifyListeners();
  }

  Future<void> spotList() async {
    setLoading(true);
    try {
      final response = await dinamicRepository.getRequest(
        url: ApiEndpoints.urlListAllSpots,
        popups: {},
        authorization: '',
      );

      final data = response?.data;
      print(data);

      if (data is List) {
        spots = data.map((e) => Spot.fromJson(e)).toList();
        notifyListeners(); // Atualiza a tela para os Widgets que estão ouvindo
      } else {
        print('❌ Esperava uma lista, mas retornou outro tipo');
      }
    } catch (e, stackTrace) {
      print('❌ Erro ao carregar lista global: $e');
      print(stackTrace);
    } finally {
      setLoading(false);
    }
  }

  Future<void> createSpot() async {
    btnController.start();
    if (_selectedSpot == null) return;

    // pega nome e valor dos controllers
    final name = nameController.text;
    //final value = productValue.text;
    String? token = await secureStorageRepository.getItem('accessToken');

    final response = await dinamicRepository.postRequest(
      body: {
        "name": name,
        //"value": value,
        "latitude": _selectedSpot!.latitude,
        "longitude": _selectedSpot!.longitude
      },
      url: ApiEndpoints.urlListCreateSpots,
      popups: {},
      authorization: 'Bearer $token',
    );

    // limpar campos depois de criar
    btnController.stop();
    nameController.clear();
    productValue.clear();
    _selectedSpot = null;
    notifyListeners();
  }

}