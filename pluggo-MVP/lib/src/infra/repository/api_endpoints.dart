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
  static String get urlRegisterAccount => "$baseUrl/v1/users";
  static String get urlListProducts => "$baseUrl/products";
  static String get urlListGlobalProducts => "$baseUrl/products/global";
  static String get urlCopyProduct => "$baseUrl/products/copy-to-personal";
  static String get urlComment => "$baseUrl/products/comments";
  static String get urlUsersList => "$baseUrl/v1/users/search/userName";
  static String get urlFollowUser => "$baseUrl/v1/follows";


//static String get urlReadNotification => "$baseUrl/";
}