
import 'package:pluggo/src/infra/dto/product_dto.dart';

import '../../../main.dart';
import '../../infra/factory/repository_factory.dart';
import 'package:auto_route/auto_route.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../presentation/provider/theme_provider.dart';
import '../../presentation/styles/styles.dart';
import '../../provider/style/home_provider.dart';
import '../../provider/user_provider.dart';

@RoutePage()
class HomeRouter extends StatelessWidget {
  const HomeRouter({super.key});

  @override
  Widget build(BuildContext context) {
    final userProvider = Provider.of<UserProvider>(context, listen: false);
    final productProvider = Provider.of<ProductProvider>(context, listen: false);

    return ChangeNotifierProvider<HomeProvider1>(
      create: (context) => HomeProvider1(
        userProvider,
        getIt<RepositoryFactory>().getSecureStorageRepository(),
        productProvider,
        getIt<RepositoryFactory>().getDinamicRepository(),
      ),
      child: ThemeProvider(
        theme: loginTheme, //influencia nas cores
        child: const AutoRouter(),
      ),
    );
  }
}