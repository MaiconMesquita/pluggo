import 'dart:math';

import 'package:pluggo/utils/device_dimensions.dart';

import '../../config/routes/app_routes.dart';
import '../../config/routes/app_routes.gr.dart';
import '../../domain/entity/device.dart';
import '../provider/theme_provider.dart';
import '../styles/spacings.dart';
import '../styles/styles.dart';
import '../styles/typography.dart';
import '../widgets/button/icon_button.dart';
import 'package:auto_route/auto_route.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';

class LayoutFirstAccess extends StatefulWidget {
  final String catchPhrase;
  final Widget child;
  final bool loginBackground;
  final bool showTokenButton;

  const LayoutFirstAccess(
      {super.key,
      required this.child,
      this.showTokenButton = true,
      this.loginBackground = true,
      required this.catchPhrase});

  @override
  State<LayoutFirstAccess> createState() => _LayoutFirstAccessState();
}

class _LayoutFirstAccessState extends State<LayoutFirstAccess> {
  @override
  void initState() {
    super.initState();
    SystemChrome.setEnabledSystemUIMode(SystemUiMode.edgeToEdge);
  }

  @override
  void dispose() {
    SystemChrome.restoreSystemUIOverlays();
    super.dispose();
  }

  //Image selectedBackground = homeBackgrounds[Random().nextInt(homeBackgrounds.length)];

  @override
  Widget build(BuildContext context) {
    //set sustem nav bar transparent
    return AnnotatedRegion<SystemUiOverlayStyle>(
      value: Device.androidRelease > 9
          ? SystemUiOverlayStyle(
              systemNavigationBarColor: Colors.transparent,
              systemNavigationBarDividerColor: Colors.transparent,
              systemNavigationBarContrastEnforced: false,
              systemNavigationBarIconBrightness:
                  widget.loginBackground ? Brightness.dark : Brightness.light,
              statusBarIconBrightness:Brightness.light, //aqui são os icones das notificações
                 // isLight ? Brightness.dark : Brightness.light,
              statusBarColor: Colors.transparent,
            )
          : SystemUiOverlayStyle(
              systemNavigationBarIconBrightness: Brightness.dark,
              systemNavigationBarColor:
                  ThemeProvider.of(context).themeColors.background),
      child: Scaffold(
        backgroundColor: ThemeProvider.of(context).themeColors.primaryContainer,
        body: Stack(
          children: [
            Container(
              alignment: Alignment.topCenter,
              margin: EdgeInsets.only(
                top: MediaQuery.of(context).padding.top +
                    HeightSpacing.customWithFixed(82),
              ),
              child: SizedBox(
                width: DeviceDimensions.screenWidth,
                height: HeightSpacing.customWithFixed(100),
                child: SizedBox.shrink(),
              ),
            ),
            //widget.loginBackground == true ?
            Positioned(
              bottom: 0,
              left: -MediaQuery.of(context).size.width * 0.2,
              right: -MediaQuery.of(context).size.width * 0.2,
              child: Container(
                padding:
                    EdgeInsets.symmetric(vertical: HeightSpacing.extraSmall),
                child: SafeArea(
                  top: false,
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.start,
                    children: [
                      Container(
                        alignment: Alignment.centerLeft,
                        padding: EdgeInsets.only(
                            bottom: HeightSpacing.customWithFixed(20)),
                        constraints: const BoxConstraints(maxWidth: 700),
                        width: MediaQuery.of(context).size.width * 0.90,
                        child: Text(
                          widget.catchPhrase,
                          style: AppTypography.loginGreetings(context),
                          textAlign: TextAlign.start,
                        ),
                      ),
                      Container(
                        constraints: const BoxConstraints(maxWidth: 700),
                        width: MediaQuery.of(context).size.width * 0.90,
                        child: widget.child,
                      ),
                      if (widget.showTokenButton)
                        Column(
                          children: [
                            TextButton(
                              onPressed: () {
                                //context.router.push(ForgotPasswordRoute());
                              },
                              style: TextButton.styleFrom(
                                padding: EdgeInsets.zero,
                              ),
                              child: Text(
                                'Esqueci minha senha',
                                style: AppTypography.infoItem(context),
                              ),
                            ),
                            SizedBox(height: 20,),
                          ],
                        ),
                    ],
                  ),
                ),
              ),
            ),
            /*
                : Positioned(
              bottom: 0,
              left: 0,
              right: 0,
              child: Container(
                decoration: const BoxDecoration(),
                padding: EdgeInsets.symmetric(vertical: HeightSpacing.smallMedium),
                child: SafeArea(
                  top: false,
                  child: Column(mainAxisSize: MainAxisSize.max, children: [
                    Container(
                      constraints: const BoxConstraints(maxWidth: 700),
                      width: MediaQuery.of(context).size.width * 0.90,
                      child: widget.child,
                    ),
                  ]),
                ),
              ),
            ),


            if (widget.showBackButton)
              Positioned(
                top: MediaQuery.of(context).padding.top,
                left: 0,
                child: SvgIconButton(
                  color: isLight ? ThemeProvider.of(context).appColors.black : ThemeProvider.of(context).appColors.white,
                  padding: EdgeInsets.only(
                    left: WidthSpacing.medium,
                    right: WidthSpacing.medium,
                    top: HeightSpacing.medium,
                    bottom: HeightSpacing.medium,
                  ),
                  iconPath: 'assets/icons/voltar.png',
                  size: 24,
                  onPressed: context.router.maybePop,
                ),
              ),

             */
          ],
        ),
      ),
    );
  }
}
