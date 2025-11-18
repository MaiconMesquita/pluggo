import 'package:pluggo/src/config/routes/app_routes.dart';
import 'package:flutter/widgets.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:onesignal_flutter/onesignal_flutter.dart';
import 'package:provider/provider.dart';

import '../../../../utils/warning_messages.dart';
import '../../../provider/user_provider.dart';

class AppNotification {
  static Future<void> init() async {
    OneSignal.Debug.setLogLevel(OSLogLevel.verbose);
    OneSignal.consentRequired(false);

    OneSignal.initialize(dotenv.env['ONE_SIGNAL_ID'] ?? '');

    bool status = OneSignal.Notifications.permission;

    if (!status) {
      status = await OneSignal.Notifications.requestPermission(false);
    }
    if (!status) return;

    String lastMessageJson = '';

    OneSignal.Notifications.addClickListener((event) {
      if (event.notification.jsonRepresentation() == lastMessageJson) return;
      lastMessageJson = event.notification.jsonRepresentation();
      WidgetsBinding.instance.addPostFrameCallback((_) {
        var userProvider = Provider.of<UserProvider>(appRouter.navigatorKey.currentContext!, listen: false);
        print(event.notification.jsonRepresentation());

        if (userProvider.id != '') {
          if (event.notification.additionalData?['type'] == 'representive') {
            ShowPopUp.showConfirmRepresentativePopUp(id: event.notification.additionalData?['detailId'], title: event.notification.title!);
          }
        }
      });
    });

    OneSignal.Notifications.addForegroundWillDisplayListener((event) {
      event.notification.display();
    });
  }
}