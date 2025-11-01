
import 'package:pluggo/src/infra/dto/google_event_dto.dart';
import 'package:pluggo/src/infra/dto/product_dto.dart';
import 'package:pluggo/src/presentation/provider/main_provider.dart';

import '../../../main.dart';
import '../../infra/dto/product_global_dto.dart';
import '../../infra/dto/users_dto.dart';
import '../../infra/factory/repository_factory.dart';
import 'package:auto_route/auto_route.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../presentation/provider/theme_provider.dart';
import '../../presentation/styles/styles.dart';
import '../../provider/style/home_provider.dart';
import '../../provider/user_provider.dart';

@RoutePage()
class MainRouter extends StatelessWidget {
  const MainRouter({super.key});

  @override
  Widget build(BuildContext context) {
    final userProvider = Provider.of<UserProvider>(context, listen: false);
    final googleProvider = Provider.of<GoogleEventProvider>(context, listen: false);
    final productProvider = Provider.of<ProductProvider>(context, listen: false);
    final productGlobalProvider = Provider.of<ProductGlobalProvider>(context, listen: false);
    final usersProvider = Provider.of<UsersProvider>(context, listen: false);


    return ChangeNotifierProvider<MainProvider>(
      create: (context) => MainProvider(
        userProvider,
        googleProvider,
        getIt<RepositoryFactory>().getSecureStorageRepository(),
        getIt<RepositoryFactory>().getDinamicRepository(),
          productProvider,
          productGlobalProvider,
          usersProvider
      ),
      child: ThemeProvider(
        theme: loginTheme, //influencia nas cores
        child: const AutoRouter(),
      ),
    );
  }
}