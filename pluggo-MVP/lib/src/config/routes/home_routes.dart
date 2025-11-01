

import 'package:pluggo/src/presentation/screens/forms_screen.dart';

import 'app_routes.gr.dart';
import 'package:auto_route/auto_route.dart';

final homeRouter = CustomRoute(
  path: '/home',
  page: HomeRouter.page,
  barrierDismissible: false,
  children: [
    CustomRoute(path: '', page: FirstAccessRoute.page, barrierDismissible: false, transitionsBuilder: TransitionsBuilders.fadeIn, initial: true),
    CustomRoute(path: 'forms', page: FormsRoute.page, barrierDismissible: false, transitionsBuilder: TransitionsBuilders.fadeIn,initial: false),

  ],
);