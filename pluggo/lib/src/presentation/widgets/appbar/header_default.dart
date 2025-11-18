import '../../../presentation/provider/theme_provider.dart';
import '../../../config/routes/app_routes.dart';
import 'package:auto_route/auto_route.dart';
import 'package:flutter/material.dart';
import '../../../presentation/styles/styles.dart';
import '../../styles/spacings.dart';
import '../../styles/typography.dart';

class HeaderDefault extends StatelessWidget {
  final String? title;
  final Color? color;
  final String? subtitle;
  final bool? closeButton;
  final bool? backButton;
  final bool? pageReturn;
  final double? topPadding;
  final VoidCallback? backCallback;

  const HeaderDefault({
    super.key,
    this.title,
    this.color,
    this.pageReturn = false,
    this.topPadding = 0,
    this.closeButton = true,
    this.backButton = true,
    this.subtitle,
    this.backCallback,
  });

  @override
  Widget build(BuildContext context) {
    final Color effectiveColor = color ?? ThemeProvider.of(context).appColors.white;

    return LayoutBuilder(builder: (context, constraints) {
      return SafeArea(
        child: Container(
          alignment: Alignment.center,
          padding: EdgeInsets.only(top: HeightSpacing.small, bottom: HeightSpacing.medium),
          child: Stack(
            alignment: Alignment.center,
            children: [
              // Título e subtítulo centralizados
              Container(
                alignment: Alignment.center,
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.start,
                  crossAxisAlignment: CrossAxisAlignment.center,
                  children: [
                    Text(
                      title ?? '',
                      style: AppTypography.appBarTitle(context),
                    ),
                    if (subtitle != null)
                      Text(
                        subtitle!,
                        style: AppTypography.appBarSubtitle(context),
                      ),
                  ],
                ),
              ),
              if (backButton == true)
                Positioned(
                  left: 0,
                  child: IconButton(
                    icon: Icon(Icons.arrow_back, color: effectiveColor),
                    iconSize: ThemeProvider.of(context).sizes.iconSizeHeader,
                    padding: EdgeInsets.all(HeightSpacing.medium),
                    onPressed: () {
                      backCallback?.call();
                      context.router.maybePop();
                    },
                  ),
                ),
              if (closeButton == true)
                Positioned(
                  right: 0,
                  child: IconButton(
                    icon: Icon(Icons.close, color: effectiveColor),
                    iconSize: ThemeProvider.of(context).sizes.iconSizeHeader,
                    padding: EdgeInsets.all(HeightSpacing.medium),
                    onPressed: () {
                      if (pageReturn == true) {
                        context.router.maybePop();
                      } else {
                        context.router.popUntilRoot();
                      }
                    },
                  ),
                ),
            ],
          ),
        ),
      );
    });
  }
}
