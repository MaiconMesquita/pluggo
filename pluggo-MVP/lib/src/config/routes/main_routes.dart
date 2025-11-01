

import 'app_routes.gr.dart';
import 'package:auto_route/auto_route.dart';

final mainRouter = CustomRoute(
  path: '/main',
  page: MainRouter.page,
  barrierDismissible: false,
  children: [
    CustomRoute(path: '', page: MainRoute.page, barrierDismissible: false, transitionsBuilder: TransitionsBuilders.fadeIn, initial: true),
    CustomRoute(path: '', page: CreateProductRoute.page, barrierDismissible: false, transitionsBuilder: TransitionsBuilders.fadeIn, initial: false),
    CustomRoute(path: '', page: GlobalProductRoute.page, barrierDismissible: false, transitionsBuilder: TransitionsBuilders.fadeIn, initial: false),
    CustomRoute(path: '', page: ProductDetailsRoute.page, barrierDismissible: false, transitionsBuilder: TransitionsBuilders.fadeIn, initial: false),
    CustomRoute(path: '', page: UsersListRoute.page, barrierDismissible: false, transitionsBuilder: TransitionsBuilders.fadeIn, initial: false),

  ],
);