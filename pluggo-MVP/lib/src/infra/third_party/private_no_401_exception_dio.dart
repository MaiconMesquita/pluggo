import '../../../../main.dart';
import '../../infra/factory/service_factory.dart';
import 'package:dio/dio.dart';
//import 'package:app/src/utils/log_utils.dart';
//import 'package:flutter_dotenv/flutter_dotenv.dart';

class PrivateNo401ExceptionDio {
  final Dio dio;

  PrivateNo401ExceptionDio(this.dio);

  Dio getInstance() {
    dio.interceptors.clear();
    dio.interceptors.add(InterceptorsWrapper(
      onRequest: (options, handler) async {
        String? accessToken;


        //options.baseUrl = dotenv.env['BASE_URL'] ?? '';
        options.baseUrl = '';

        options.headers['Authorization'] = 'Bearer $accessToken';
        //options.headers['Api-Key'] = dotenv.env['API_KEY'] ?? '';

        options.headers['Api-Key'] =  '';
/*
        LogUtils.log('-------------------------');
        LogUtils.log(options.headers);
        LogUtils.log(options.baseUrl);
        LogUtils.log('-------------------------');


 */
        return handler.next(options);
      },
      /*
      onError: (DioException exception, handler) async {
        LogUtils.log('-------------------------');
        LogUtils.log(exception.error);
        LogUtils.log(exception.message);
        LogUtils.log(exception.response);
        LogUtils.log(exception.response?.statusCode.toString() ?? '');
        LogUtils.log(exception.response?.data);
        LogUtils.log('-------------------------');

        return handler.next(exception);
      },
      onResponse: (response, handler) async {
        LogUtils.log(response.data);
        LogUtils.log(response.statusMessage);
        LogUtils.log(response.statusCode);
        return handler.next(response);
      },

       */
    ));
    return dio;
  }
}