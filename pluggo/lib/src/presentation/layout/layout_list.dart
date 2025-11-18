import 'package:pluggo/src/domain/entity/device.dart';
import 'package:pluggo/src/presentation/provider/theme_provider.dart';
import 'package:pluggo/src/presentation/styles/styles.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';

import '../styles/spacings.dart';


class LayoutList extends StatelessWidget {
  final Widget appBar;
  final List<Widget> children;
  final bool isLoading;
  final bool isDarkBackground;

  const LayoutList({
    super.key,
    required this.appBar,
    required this.children,
    this.isLoading = false,
    this.isDarkBackground = false,
  });

  @override
  Widget build(BuildContext context) {
    SystemChrome.setEnabledSystemUIMode(SystemUiMode.edgeToEdge);
    return AnnotatedRegion<SystemUiOverlayStyle>(
      value: Device.androidRelease > 9
          ? SystemUiOverlayStyle(
        systemNavigationBarColor: Colors.transparent,
        systemNavigationBarDividerColor: Colors.transparent,
        systemNavigationBarContrastEnforced: false,
        systemNavigationBarIconBrightness: ThemeProvider.of(context).themeColors.brightness,
        statusBarIconBrightness: ThemeProvider.of(context).themeColors.brightness,
        statusBarColor: Colors.transparent,
      )
          : SystemUiOverlayStyle(
        systemNavigationBarIconBrightness: ThemeProvider.of(context).themeColors.brightness,
        systemNavigationBarColor: ThemeProvider.of(context).themeColors.background,
        statusBarIconBrightness: ThemeProvider.of(context).themeColors.brightness,
      ),
      child: Scaffold(
        backgroundColor:ThemeProvider.of(context).themeColors.background,
        body: SafeArea(
          top: false,
          child: Column(
            children: [
              appBar,
              if (isLoading == true) ...[
                const Spacer(),
                Center(
                  child: CircularProgressIndicator(
                    color: isDarkBackground? ThemeProvider.of(context).appColors.white : ThemeProvider.of(context).appColors.primary,
                  ),
                ),
                const Spacer(),
              ],
              if (isLoading == false)
                Expanded(
                  child: Container(
                    padding: EdgeInsets.symmetric(horizontal: WidthSpacing.medium),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [...children,
                       const Spacer(),
                      ],
                    ),
                  ),
                )
            ],
          ),
        ),
      ),
    );
  }
}