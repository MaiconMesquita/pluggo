import 'dart:convert';

import 'package:dio/dio.dart';

import '../../../utils/warning_messages.dart';
import '../../domain/exceptions/api_exception.dart';
import '../../domain/exceptions/handler.dart';
import '../../presentation/widgets/pop_up/popUp_config.dart';

class DinamicRepository {
  final Dio apiPublic;

  DinamicRepository({required this.apiPublic});

  Future<Response?> getRequest({
    required String url,
    Map<String, dynamic>? parameters,
    required Map<int, PopUpConfig> popups,
    required String? authorization, // Header de autorização
  }) async {
    print('veio pro get dinamico $url e $parameters');
    try {
      Response response = await apiPublic.get(
          url,
          queryParameters: parameters,
          options: Options(
            headers: {'Authorization': authorization?? ''},
            validateStatus: (status) => status != null && status < 500, // Permite até 499
    )
      );
      print(response.statusCode);
      print(response.data);

      if (popups.containsKey(response.statusCode)) {
        ShowPopUp.showNotification(popups[response.statusCode]!);
      }

      return response;
    } catch (e, stackTrace) {
  print("Erro durante login: $e");
  print("Stack trace: $stackTrace");

  Handler.apiException(e, stackTrace);
  throw ApiException();
  }
  }

  Future<Response?> postRequest({
    required String url,
    required Map<String, dynamic> body,
    required Map<int, PopUpConfig> popups,
    required String authorization, // Header de autorização
  }) async {
    try {
      final response = await apiPublic.post(
        url,
        data: jsonEncode(body),
        options: Options(
          headers: {'Authorization': authorization},
          validateStatus: (status) => status != null && status < 500, // Permite até 499
        ),
      );

      print("Resposta recebida: ${response.statusCode}");
      print("Dados da resposta: ${response.data}");

      // Exibir popup caso o status esteja no mapeamento
      if (popups.containsKey(response.statusCode)) {
        ShowPopUp.showNotification(popups[response.statusCode]!);
      }

      return response;
    } catch (e, stackTrace) {
      print("Erro durante login: $e");
      print("Stack trace: $stackTrace");

      Handler.apiException(e, stackTrace);
      throw ApiException();
    }
  }

  Future<Response?> putRequest({
    required String url,
    required Map<String, dynamic> body,
    required Map<int, PopUpConfig> popups,
    required String authorization, // Header de autorização
    Map<String, dynamic>? parameters,
  }) async {
    try {
      final response = await apiPublic.put(
        url,
        data: jsonEncode(body),
        queryParameters: parameters,
        options: Options(
          headers: {'Authorization': authorization},
          validateStatus: (status) => status != null && status < 500, // Permite até 499
        ),
      );

      print("Resposta recebida: ${response.statusCode}");
      print("Dados da resposta: ${response.data}");

      // Exibir popup caso o status esteja no mapeamento
      if (popups.containsKey(response.statusCode)) {
        print('mostrou popup');
        ShowPopUp.showNotification(popups[response.statusCode]!);
      }

      return response;
    } catch (e, stackTrace) {
      print("Erro durante login: $e");
      print("Stack trace: $stackTrace");

      Handler.apiException(e, stackTrace);
      throw ApiException();
    }
  }
}
