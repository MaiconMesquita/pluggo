import '../../domain/entity/device.dart';
import '../../presentation/provider/theme_provider.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';


class LayoutMain extends StatefulWidget {
  final Widget child;
  final Widget? appBar;
  final double? horizontalPadding;
  final bool showFooterMain;
  final bool isLoading;
  final bool isLoadingPage;
  final String? loadingText;
  final bool bottomSafeArea;
  final int background;
  final bool isBackgroundDark;
  final bool warning;
  final bool isAutomaticAntecipation;
  final bool isFullScreen;

  const LayoutMain({
    super.key,
    required this.child,
    this.horizontalPadding,
    this.appBar,
    this.isBackgroundDark = true,
    this.showFooterMain = false,
    this.isLoading = false,
    this.isLoadingPage = false,
    this.loadingText,
    this.bottomSafeArea = false,
    this.background = 3,
    this.warning = true,
    required this.isAutomaticAntecipation,
    this.isFullScreen = false
  });

  @override
  State<LayoutMain> createState() => _LayoutMainState();
}

class _LayoutMainState extends State<LayoutMain> {
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

  Widget build(BuildContext context) {
    return AnnotatedRegion<SystemUiOverlayStyle>(
      value: Device.androidRelease > 9
          ? SystemUiOverlayStyle(
        systemNavigationBarColor: Colors.transparent,
        systemNavigationBarDividerColor: Colors.transparent,
        systemNavigationBarContrastEnforced: false,
        systemNavigationBarIconBrightness: ThemeProvider
            .of(context)
            .themeColors
            .brightness,
        statusBarIconBrightness: widget.isBackgroundDark
            ? Brightness.light
            : Brightness.dark,
        statusBarColor: Colors.transparent,
      )
          : SystemUiOverlayStyle(
        systemNavigationBarIconBrightness: widget.isBackgroundDark
            ? Brightness.light
            : Brightness.dark,
        systemNavigationBarColor: ThemeProvider
            .of(context)
            .themeColors
            .background,
        statusBarIconBrightness: ThemeProvider
            .of(context)
            .themeColors
            .brightness,
      ),
      child: PopScope(
        canPop: !widget.isLoadingPage,
        child: Scaffold(
          backgroundColor: widget.isLoadingPage ? ThemeProvider
              .of(context)
              .themeColors
              .background : Colors.transparent,
          resizeToAvoidBottomInset: true,
          body: widget.isLoadingPage
              ? Center(
            child: CircularProgressIndicator(
              color: ThemeProvider
                  .of(context)
                  .appColors
                  .primary,
            ),
          )
              : Stack(
            children: [
              Container(
                color: ThemeProvider
                    .of(context)
                    .appColors
                    .white, // ou qualquer cor que quiser
              ),
              SafeArea(
                top: false,
                bottom: widget.bottomSafeArea,
                child: LayoutBuilder(
                  builder: (context, constraints) {
                    return Column(
                      children: [
                        // AppBar opcional
                        if (widget.appBar != null) widget.appBar!,

                        // Conteúdo principal com scroll
                        Expanded(
                          child: widget.isFullScreen
                              ? widget.child // GoogleMap funciona aqui!
                              : SingleChildScrollView(
                            physics: const AlwaysScrollableScrollPhysics(),
                            child: ConstrainedBox(
                              constraints: BoxConstraints(minWidth: constraints.maxWidth),
                              child: IntrinsicHeight(
                                child: Align(
                                  alignment: Alignment.center,
                                  child: widget.child,
                                ),
                              ),
                            ),
                          ),
                        )



                      ],
                    );
                  },
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
