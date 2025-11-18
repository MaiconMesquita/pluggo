import '../../utils/warning_messages.dart';
import '../domain/exceptions/api_exception.dart';
import '../domain/exceptions/no_popup_exception.dart';
import '../domain/exceptions/permission_exception.dart';
import '../domain/exceptions/processing_exception.dart';
//import 'package:app/src/utils/warning_messages.dart';
//import 'package:app/src/utils/log_utils.dart';
import 'package:flutter/foundation.dart';

class CustomChangeNotifier extends ChangeNotifier {
  bool disposed = false;

  @override
  void dispose() {
    disposed = true;
    disposeAdditional();
    super.dispose();
  }

  @override
  void notifyListeners() {
    if (!disposed) {
      super.notifyListeners();
    }
  }

  Future<void> handleError(dynamic e, StackTrace? stackTrace, {bool? showPopUp = true, bool? isPortrait}) async {
    if (showPopUp == true) {
      if (e is ApiException) {
        await ShowPopUp.showNotification2(e.message, isPortrait: isPortrait == false ? false : true);
        return;
      }
      if (e is PermissionException) {
        await ShowPopUp.showNotification2(e.message, isPortrait: isPortrait == false ? false : true);
        return;
      }
      if (e is ProcessingException) {
        await ShowPopUp.showNotification2(e.message, isPortrait: isPortrait == false ? false : true);
        return;
      }
    }

    //LogUtils.log(e);
    //LogUtils.log(stackTrace);
    if (e is NoPopUpException) return;
    if (showPopUp == true) await ShowPopUp.showNotification2(AlertMessages.genericError, isPortrait: isPortrait == false ? false : true);
  }

  void disposeAdditional() {}
}