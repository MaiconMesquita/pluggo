import '../../../utils/warning_messages.dart';

class ProcessingException implements Exception {
  final PopUp message = AlertMessages.processing;

  ProcessingException();

  String getErrorMessage() {
    return AlertMessages.processing.description;
  }

  String getErrorTitle() {
    return AlertMessages.processing.title;
  }
}