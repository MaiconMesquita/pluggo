import 'package:dio/dio.dart';
//import 'package:app/src/utils/log_utils.dart';
//import 'package:flutter_dotenv/flutter_dotenv.dart';

class PublicDio {
  final Dio dio;

  PublicDio(this.dio);

  Dio getInstance() {
    dio.interceptors.clear();
    dio.interceptors.add(InterceptorsWrapper(
      onRequest: (options, handler) async {
        //options.baseUrl = dotenv.env['BASE_URL'] ?? '';
        options.baseUrl = "";

        //options.headers['Api-Key'] = dotenv.env['API_KEY'] ?? '';
        options.headers['Api-Key'] = "";

        return handler.next(options);
      },

    ));
    return dio;
  }
}