import '../domain/service/secure_storage_service.dart';
import 'package:auto_route/auto_route.dart';
import 'package:flutter/material.dart';
import '../config/routes/app_routes.gr.dart';

import '../config/routes/app_routes.dart';
import '../domain/entity/device.dart';
import '../../utils/device_dimensions.dart';
import '../infra/third_party/notification/one_signal.dart';

@RoutePage()
class SplashScreenPage extends StatefulWidget {
  const SplashScreenPage({super.key});

  @override
  State<SplashScreenPage> createState() => _SplashScreenPageState();
}

class _SplashScreenPageState extends State<SplashScreenPage> {
  @override
  void initState() {
    super.initState();
    _initialize();
  }

  Future<void> _initialize() async {
    await Device.requestPermission();
    await AppNotification.init();
      await Device.init();
      // para usar os dados de persistencia, por enquanto ele da erro
      await SecureStorageService.init();
      appRouter.replaceAll([const HomeRouter()]);
      //AppLink.init();

  }

  @override
  Widget build(BuildContext context) {
    DeviceDimensions.init(context);

    return Scaffold(
      body: Container(
        color: Colors.blue,
        //decoration: const BoxDecoration(color: AppColors.darkPurple),
        child: const Center(
          child: Text('PlugGo'),
        ),
      ),
    );
  }
}