import 'package:firebase_auth/firebase_auth.dart';
import 'package:firebase_core/firebase_core.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:dio/dio.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:get_it/get_it.dart';
import 'package:pluggo/src/config/colors.dart';
import 'package:pluggo/src/infra/dto/google_event_dto.dart';
import 'package:pluggo/src/infra/dto/product_dto.dart';
import 'package:pluggo/src/infra/dto/product_global_dto.dart';
import 'package:pluggo/src/infra/dto/users_dto.dart';
import 'package:pluggo/src/presentation/provider/theme_provider.dart';
import 'package:pluggo/src/provider/user_provider.dart';
import 'package:pluggo/utils/services/preferences_service.dart';
import 'package:provider/provider.dart';

import 'firebase_options.dart';
import 'src/config/routes/app_routes.dart';
import 'src/config/theme.dart';
import 'src/domain/entity/device.dart';
import 'src/infra/factory/repository_factory.dart';
import 'src/infra/factory/service_factory.dart';
import 'src/infra/third_party/private_dio.dart';
import 'src/infra/third_party/private_no_401_exception_dio.dart';
import 'src/infra/third_party/public_dio.dart';

final getIt = GetIt.instance;

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await dotenv.load(fileName: ".env");
  await Firebase.initializeApp(
    options: DefaultFirebaseOptions.currentPlatform,
  );
  runApp(ThemeProvider(theme: defaultTheme, child: MyApp()));
  SystemChrome.setPreferredOrientations(
      [DeviceOrientation.portraitUp, DeviceOrientation.portraitDown]);

  //registrando e iniciando todos os getit para usar no código
  //verify e forms providers não estão aqui porque todas as informações passadas
  //por eles estão sendo gerenciadas pelo repository e service que ja estão
  //sendo chamadas aqui

  getIt.registerSingleton<FlutterSecureStorage>(const FlutterSecureStorage());

  getIt.registerSingleton<RepositoryFactory>(RepositoryFactory());
  getIt.registerSingleton<ServiceFactory>(ServiceFactory());

  getIt.registerSingleton<PublicDio>(PublicDio(Dio()));
  getIt.registerSingleton<PrivateDio>(PrivateDio(Dio()));
  getIt.registerSingleton<PrivateNo401ExceptionDio>(PrivateNo401ExceptionDio(Dio()));

}

class MyApp extends StatelessWidget {
  static final GlobalKey<NavigatorState> navigatorKey =
  GlobalKey<NavigatorState>();

  final preferencesService = PreferencesService();

  @override
  Widget build(BuildContext context) {
    SystemChrome.setEnabledSystemUIMode(SystemUiMode.edgeToEdge);
    const customPurple = Color.fromRGBO(163, 138, 221, 1);

    return ThemeProvider(
      theme: defaultTheme,
      child: MultiProvider(
        providers: [
          Provider<PreferencesService>(
            create: (_) => preferencesService,
          ),
          ChangeNotifierProvider<UserProvider>(
            create: (context) => UserProvider(),
          ),
          ChangeNotifierProvider<UsersProvider>(
            create: (context) => UsersProvider(),
          ),
          ChangeNotifierProvider<ProductProvider>(
            create: (context) => ProductProvider(),
          ),
          ChangeNotifierProvider<ProductGlobalProvider>(
            create: (context) => ProductGlobalProvider(),
          ),
          ChangeNotifierProvider<GoogleEventProvider>(
            create: (context) => GoogleEventProvider(),
          ),
        ],
        child: Builder(builder: (context) {
          ScreenUtil.init(
            context,
            designSize: const Size( 432, 984), // Substitua pelo tamanho de design base do seu app
            minTextAdapt: true,
            splitScreenMode: true,
          );
          return AnnotatedRegion<SystemUiOverlayStyle>(
            value: Device.androidRelease > 9
                ? const SystemUiOverlayStyle(
              systemNavigationBarColor: Colors.transparent,
              systemNavigationBarDividerColor: Colors.transparent,
              systemNavigationBarContrastEnforced: false,
              systemNavigationBarIconBrightness: Brightness.dark,
            )
                : SystemUiOverlayStyle(
              systemNavigationBarIconBrightness: Brightness.dark,
              systemNavigationBarColor:
              ThemeProvider.of(context).themeColors.background,
            ),
            child: MaterialApp.router(
              darkTheme: ThemeData(
                scrollbarTheme: ScrollbarThemeData(
                  thumbColor: MaterialStateProperty.all<Color>(
                      ThemeProvider.of(context).appColors.grey600),
                  trackColor: MaterialStateProperty.all<Color>(
                      ThemeProvider.of(context).appColors.greyDark),
                ),
                colorScheme: ColorScheme.fromSwatch(
                  brightness: Brightness.dark,
                  primarySwatch: Colors.blue,
                  backgroundColor:
                  ThemeProvider.of(context).themeColors.background,
                ),
              ),
              theme: ThemeData(
                scrollbarTheme: ScrollbarThemeData(
                  thumbColor: MaterialStateProperty.all<Color>(
                      ThemeProvider.of(context).appColors.grey600),
                  trackColor: MaterialStateProperty.all<Color>(
                      ThemeProvider.of(context).appColors.greyDark),
                ),
                colorScheme: ColorScheme.fromSwatch(
                  primarySwatch: Colors.blue,
                  backgroundColor:
                  ThemeProvider.of(context).themeColors.background,
                ),
              ),
              title: 'BrandsCard',
              debugShowCheckedModeBanner: false,
              routerConfig: appRouter.config(),
            ),
          );
        }),
      ),
    );
  }
}