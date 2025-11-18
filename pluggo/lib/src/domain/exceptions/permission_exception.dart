import '../../../utils/warning_messages.dart';

class PermissionException implements Exception {
  final PopUp? message;

  PermissionException([this.message]);

  String getErrorMessage() {
    return message?.description ?? AlertMessages.genericError.description;
  }

  String getErrorTitle() {
    return message?.title ?? AlertMessages.genericError.title;
  }
}