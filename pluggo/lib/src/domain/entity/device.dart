import 'dart:io';
import 'package:device_info_plus/device_info_plus.dart';
import 'package:local_auth/local_auth.dart';
import 'package:onesignal_flutter/onesignal_flutter.dart';
import 'package:permission_handler/permission_handler.dart';
import 'package:uuid/uuid.dart';
import 'package:app_tracking_transparency/app_tracking_transparency.dart';
import '../../../main.dart';
import '../../infra/factory/repository_factory.dart';
import '../../infra/repository/secure_storage_repository.dart';
import '../../infra/third_party/location/geolocator.dart';
import '../../infra/third_party/location/location.dart';
/* Não estou usando estes:
import 'package:app/src/infra/repository/error_report_repository.dart';
import 'package:app/src/utils/log_utils.dart';
 */

class Device {
  static String os = '';
  static int androidRelease = 0;
  static String deviceVersion = '';
  static String deviceId = ''; // OneSignal ID
  static String unicDeviceId = ''; // ID do dispositivo
  static String oneSignalUserID = ''; // ID do dispositivo
  static bool isBioSupported = false;
  static double latitude = 0.0; // Para armazenar a latitude
  static double longitude = 0.0; // Para armazenar a longitude

  static Future<void> init() async {
    final LocationService locationService = GeolocatorAdapter();
    try {

      final auth = LocalAuthentication();
      final deviceInfo = DeviceInfoPlugin();
      final SecureStorageRepository secureStorageRepository = getIt<RepositoryFactory>().getSecureStorageRepository();
      final position = await locationService.determinePosition();
      latitude = position.latitude;
      longitude = position.longitude;
      await secureStorageRepository.setItem('device_latitude', latitude.toString());
      await secureStorageRepository.setItem('device_longitude', longitude.toString());

      final androidInfo = Platform.isAndroid ? await deviceInfo.androidInfo : null;
      print("Informações do Android obtidas: $androidInfo");

      final iosInfo = !Platform.isAndroid ? await deviceInfo.iosInfo : null;
      print("Informações do iOS obtidas: $iosInfo");

      isBioSupported = await auth.isDeviceSupported();
      print("Suporte a biometria: $isBioSupported");

      os = androidInfo != null ? "Android" : "iOS";
      print("Sistema operacional: $os");

      //unicDeviceId = '73aeafd9-1e71-4269-aab5-d3f2e8b241e';
      //unicDeviceId = '6';

      unicDeviceId = await secureStorageRepository.getItem('device_id') ?? '';
      print('unicdevice: $unicDeviceId');
      if (unicDeviceId.isEmpty) {
        unicDeviceId = const Uuid().v4();
        await secureStorageRepository.setItem('device_id', unicDeviceId);
      }

      oneSignalUserID = await secureStorageRepository.getItem('oneSignal_userID') ?? '';
      print("OneSignal Player ID: $oneSignalUserID");
      if (oneSignalUserID.isEmpty) {
        var user = OneSignal.User;
        oneSignalUserID = user.pushSubscription.id?? '';
        print("OneSignal Player ID: ${user.pushSubscription.id}");
        await secureStorageRepository.setItem('oneSignal_userID', oneSignalUserID);
      }

      androidRelease = androidInfo != null ? int.parse(androidInfo.version.release.split('.')[0]) : 10;
      print("Versão do Android: $androidRelease");

      deviceVersion = androidInfo != null ? androidInfo.version.release : iosInfo!.systemVersion;
      print("Versão do dispositivo: $deviceVersion");

      /*
    await OneSignal.shared.getDeviceState().then((deviceState) {
      if (deviceState != null) {
        deviceId = deviceState.userId ?? '';
        print("Device ID do OneSignal: $deviceId");
      }
    });
    */
    } catch (e, stacktrace) {
      print("Erro em Device.init(): $e");
      print("Stacktrace: $stacktrace");
    }
  }




  static Future<bool> requestPermission() async {
    if (!Platform.isAndroid) {
      final location = await AppTrackingTransparency
          .requestTrackingAuthorization();
      print(location);
    }

    Map<Permission, PermissionStatus> statuses = await [
      Permission.location,
      Permission.camera,
      Permission.notification,
    ].request();

    bool denied = statuses.values.any((status) => status.isDenied || status.isPermanentlyDenied);


    return denied;
    // Handle or log permission statuses as needed
  }

  // Convert to JSON
  static Map<String, dynamic> toJson() => {
    'os': os,
    'deviceId': deviceId,
  };
}