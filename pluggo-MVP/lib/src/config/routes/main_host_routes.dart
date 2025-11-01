

import 'app_routes.gr.dart';
import 'package:auto_route/auto_route.dart';

final mainHostRouter = CustomRoute(
  path: '/mainHost',
  page: MainHostRouter.page,
  barrierDismissible: false,
  children: [
    CustomRoute(path: '', page: MainHostRoute.page, barrierDismissible: false, transitionsBuilder: TransitionsBuilders.fadeIn, initial: true),
  ],
);