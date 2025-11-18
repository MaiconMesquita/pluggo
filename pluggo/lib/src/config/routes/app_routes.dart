/*
Definição das Rotas: A lista routes define cada rota do aplicativo,
associando um caminho (path) a uma página (page). Também especifica características
como se a rota é a inicial (initial), se a transição é personalizada (transitionsBuilder),
e se a rota deve ser descartada ao navegar (barrierDismissible).

transitionsBuilder: TransitionsBuilders.slideRight - transição nesse caso é por slide o voltar
 */

import 'package:pluggo/src/config/routes/main_host_routes.dart';
import 'package:pluggo/src/config/routes/main_routes.dart';
import 'package:pluggo/src/config/routes/maps_routes.dart';

import 'app_routes.gr.dart';
import 'package:auto_route/auto_route.dart';

import 'home_routes.dart';

var appRouter = AppRouter();

@AutoRouterConfig(replaceInRouteName: 'Screen,Route')
class AppRouter extends RootStackRouter {
  @override
  List<AutoRoute> get routes => [
    CustomRoute(path: '/splash', page: SplashRoutePage.page, barrierDismissible: false, initial: true),
    homeRouter,
    mainRouter,
    mainHostRouter,
    mapsRouter
  ];
}