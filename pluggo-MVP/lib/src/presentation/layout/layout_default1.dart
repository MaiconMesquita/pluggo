import '../../domain/entity/device.dart';
import '../../presentation/layout/scroll_behavior.dart';
import '../../presentation/provider/theme_provider.dart';
import '../../presentation/styles/styles.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';

import '../styles/spacings.dart';

class DefaultLayout1 extends StatelessWidget {
  final Widget child;
  final Widget? appBar;
  final double? horizontalPadding;
  final bool showFooter;
  final bool isLoading;
  final bool isLoadingPage;
  final String? loadingText;
  final bool bottomSafeArea;
  final bool isBackgroundDark;


  const DefaultLayout1({
    super.key,
    required this.child,
    this.horizontalPadding,
    this.appBar,
    this.isBackgroundDark = true,
    this.showFooter = true,
    this.isLoading = false,
    this.isLoadingPage = false,
    this.loadingText,
    this.bottomSafeArea = true,
  });
  @override
  Widget build(BuildContext context) {

    return AnnotatedRegion<SystemUiOverlayStyle>(
      value: Device.androidRelease > 9
          ? SystemUiOverlayStyle(
        systemNavigationBarColor: Colors.transparent,
        systemNavigationBarDividerColor: Colors.transparent,
        systemNavigationBarContrastEnforced: false,
        systemNavigationBarIconBrightness: ThemeProvider.of(context).themeColors.brightness,
        statusBarIconBrightness: isBackgroundDark ? Brightness.light : Brightness.dark,
        statusBarColor: Colors.transparent,
      )
          : SystemUiOverlayStyle(
        systemNavigationBarIconBrightness: isBackgroundDark ? Brightness.light : Brightness.dark,
        systemNavigationBarColor: ThemeProvider.of(context).themeColors.background,
        statusBarIconBrightness: ThemeProvider.of(context).themeColors.brightness,
      ),
      child: PopScope(
        canPop: !isLoadingPage,
        child: Scaffold(
          backgroundColor: ThemeProvider.of(context).themeColors.primaryContainer,
          resizeToAvoidBottomInset: true,
          body: isLoadingPage
              ? Center(
            child: CircularProgressIndicator(
              color: isBackgroundDark ? ThemeProvider.of(context).appColors.white :ThemeProvider.of(context).appColors.primary,
            ),
          )
              : Stack(
            children: [
              SafeArea(
                top: false,
                bottom: bottomSafeArea,
                child: Column(
                  children: [
                    if (appBar != null)appBar!,
                    Expanded(
                      child: ScrollConfiguration(
                        behavior: MyBehavior(),
                        child: CustomScrollView(
                          physics: const ClampingScrollPhysics(),
                          slivers: <Widget>[
                            SliverFillRemaining(
                                hasScrollBody: false,
                                child: Container(
                                  //color: ThemeProvider.of(context).themeColors.background,
                                    padding: EdgeInsets.symmetric(horizontal: horizontalPadding ?? WidthSpacing.medium),
                                    child: Column(
                                      crossAxisAlignment: CrossAxisAlignment.stretch,
                                      children: [
                                        Expanded(
                                          child: isLoading
                                              ? Center(
                                            child: CircularProgressIndicator(
                                              color: ThemeProvider.of(context).appColors.white,
                                            ),
                                          )
                                              : child,
                                        ),
                                        ],
                                    )
                                )
                            ),
                          ],
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}