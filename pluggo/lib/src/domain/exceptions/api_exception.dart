import '../../../utils/warning_messages.dart';

class ApiException implements Exception {
  final PopUp? message;

  ApiException([this.message]);

  String getErrorMessage() {
    return message?.description ?? AlertMessages.genericError.description;
  }

  String getErrorTitle() {
    return message?.title ?? AlertMessages.genericError.title;
  }
}

// class ApiExceptionNoPopup implements Exception {
//   final PopUp? message;

//   ApiExceptionNoPopup([this.message]);

//   String getErrorMessage() {
//     return message?.description ?? AlertMessages.genericError.description;
//   }

//   String getErrorTitle() {
//     return message?.title ?? AlertMessages.genericError.title;
//   }
// }