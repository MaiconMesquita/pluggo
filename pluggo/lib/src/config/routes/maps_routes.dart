import 'app_routes.gr.dart';
import 'package:auto_route/auto_route.dart';

final mapsRouter = CustomRoute(
  path: '/maps',
  page: MapsRouter.page,
  barrierDismissible: false,
  children: [
    CustomRoute(path: '', page: MapsRoute.page, barrierDismissible: false, transitionsBuilder: TransitionsBuilders.fadeIn, initial: true),
    CustomRoute(path: '', page: CreateSpotRoute.page, barrierDismissible: false, transitionsBuilder: TransitionsBuilders.fadeIn),
  ],
);