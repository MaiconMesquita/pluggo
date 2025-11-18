import 'package:flutter_dotenv/flutter_dotenv.dart';

import '../../domain/entity/device.dart';

class ApiEndpoints {
  static String deviceId = Device.unicDeviceId;
  static String baseUrl = dotenv.env['BASE_URL'] ?? '';
  static String googleCalendarBaseUrl = dotenv.env['GOOGLE_CALENDAR_BASE_URL'] ?? '';
  static String oneSignalAppId = dotenv.env['ONE_SIGNAL_ID'] ?? '';
  static String oneSignalRestApiKey = dotenv.env['API_KEY_ONESIGNAL'] ?? '';

  static String get urlCreateEvent => "$googleCalendarBaseUrl/primary/events";
  static String get urlLogin => "$baseUrl/signin/general";
  static String get urlCreateAccount => "$baseUrl/signup/create-account";
  static String get urlListAllSpots => "$baseUrl/host/list-spots";
  static String get urlListSpots => "$baseUrl/host/list-all-spots";
  static String get urlListCreateSpots => "$baseUrl/host/create-charge-spot";



//static String get urlReadNotification => "$baseUrl/";
}