

import '../../../main.dart';
import '../repository/dinamic_repository.dart';
import '../repository/secure_storage_repository.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

import '../third_party/public_dio.dart';


class RepositoryFactory {

  //UserRepository? userRepository;
  DinamicRepository? dinamicRepository;
  SecureStorageRepository? secureStorageRepository;


  SecureStorageRepository getSecureStorageRepository() {
    if (secureStorageRepository != null) return secureStorageRepository!;
    secureStorageRepository = SecureStorageRepository(getIt<FlutterSecureStorage>());
    return secureStorageRepository!;
  }

  DinamicRepository getDinamicRepository() {
    if (dinamicRepository != null) return dinamicRepository!;
    dinamicRepository = DinamicRepository(apiPublic: getIt<PublicDio>().getInstance());
    return dinamicRepository!;
  }
}