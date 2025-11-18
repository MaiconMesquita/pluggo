import '../../../utils/warning_messages.dart';

class NoPopUpException implements Exception {
  final PopUp? message;

  NoPopUpException([this.message]);

  String getErrorMessage() {
    return message?.description ?? AlertMessages.genericError.description;
  }

  String getErrorTitle() {
    return message?.title ?? AlertMessages.genericError.title;
  }
}