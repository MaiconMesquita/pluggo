import '../../domain/exceptions/api_exception.dart';
import '../../domain/exceptions/processing_exception.dart';
//import '../../repository/error_report_repository.dart';
import '../../../utils/warning_messages.dart';
import 'package:dio/dio.dart';

class Handler {
  static void apiResponse(Response response) {
    if (response.data is Map && response.data.containsKey("status")) {
      if (response.data["status"] == "rejected") {
        throw ApiException(AlertMessages.rejectedTransaction);
      }
    }
    // if (response.statusCode != 200) {
    //   throw ApiException(popMessage);
    // }
  }

  static void apiException(dynamic e, StackTrace stackTrace) {
    if (e is DioException) {
      if (e.response?.statusCode == 503) throw ProcessingException();
      if (e.response != null && e.response!.data != null && e.response!.data["message"] != null) {
        final String message = e.response?.data["message"] ?? '';
        if (message.contains('There is already an approved or running KYC')) throw ApiException(AlertMessages.kycAlreadyInProgress);
        if (message.contains('Account already verified')) throw ApiException(AlertMessages.accountAlreadyVerified);
        if (e.response?.data["friendlyTitle"] != null && e.response?.data["friendlyMessage"] != null) {
          String title = e.response?.data["friendlyTitle"].replaceAll("\\n", "\n") ?? '';
          String message = e.response?.data["friendlyMessage"].replaceAll("\\n", "\n") ?? '';
          throw ApiException(PopUp(title: title, description: message));
        }
      }
      //if (e.response?.statusCode == 400) ErrorReportRepository.reportError(error: e, stackTrace: stackTrace);
    } else {
      //ErrorReportRepository.reportError(error: e, stackTrace: stackTrace);
    }
  }
}