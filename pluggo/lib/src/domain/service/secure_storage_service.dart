import 'dart:convert';
import '../../../main.dart';
import '../../infra/factory/repository_factory.dart';
import '../../infra/repository/secure_storage_repository.dart';
import '../../config/routes/app_routes.dart';
import 'package:local_auth/local_auth.dart';
import 'package:provider/provider.dart';
//import '../entity/register.dart';

class SecureStorageService {
  static final SecureStorageRepository _secureStorageRepository = getIt<RepositoryFactory>().getSecureStorageRepository();
  static final LocalAuthentication auth = LocalAuthentication();

  static Future<void> init() async {
    try {
    } catch (e, stackTrace) {
      //ErrorReportRepository.reportError(error: e, stackTrace: stackTrace);
      //LogUtils.log(e);
      //LogUtils.log('Deleting all secure storage data');
      //await const FlutterSecureStorage().deleteAll();
      //await init();
      print('$e erro em: $stackTrace');
    }
  }
}