
import '../../presentation/provider/theme_provider.dart';
import '../../presentation/styles/styles.dart';
import 'package:flutter/material.dart';

class AppDecoration {
  static BoxDecoration backgroundTopGradient(BuildContext context) {
    return BoxDecoration(
      gradient: LinearGradient(
        begin: Alignment.topCenter,
        end: Alignment.center,
        colors: [
          Colors.black.withOpacity(1), // Cor do gradiente no início (mais escuro)
          Colors.black.withOpacity(0), // Cor do gradiente no final (mais claro)
        ],
      ),
    );
  }

  static BoxDecoration backgroundBottomGradient(BuildContext context) {
    return BoxDecoration(
      gradient: LinearGradient(
        begin: Alignment.bottomCenter,
        end: Alignment.topCenter,
        colors: [
          Colors.black.withOpacity(1), // Cor do gradiente no início (mais escuro)
          Colors.white.withOpacity(0.1), // Cor do gradiente no final (mais claro)
        ],
      ),
    );
  }

  static BoxDecoration radiusBoxDecoration(BuildContext context) {
    return BoxDecoration(
      color: ThemeProvider.of(context).themeColors.primaryContainer,
      borderRadius: BorderRadius.circular(themeSizes.borderRadius),
      border: Border.all(
        color: ThemeProvider.of(context).themeColors.border,
        width: 1.0,
      ),
    );
  }

  static BoxDecoration radiusBoxDecorationError(BuildContext context) {
    return BoxDecoration(
      color: ThemeProvider.of(context).themeColors.inputBackground,
      borderRadius: BorderRadius.circular(themeSizes.borderRadius),
      border: Border.all(
        color: ThemeProvider.of(context).appColors.error,
        width: 2.0,
      ),
    );
  }

  static BoxDecoration dropDownDecorationWhite(BuildContext context) {
    return BoxDecoration(
      color: Colors.transparent,
      borderRadius: BorderRadius.circular(themeSizes.borderRadius),
      border: Border.all(
        color: Colors.transparent,
        width: 1.0,
      ),
    );
  }

  static BoxDecoration warningDecorationWhite(BuildContext context) {
    return BoxDecoration(
      color: Colors.lightBlueAccent.withOpacity(0.2),
      borderRadius: BorderRadius.circular(themeSizes.smallMedium),
      border: Border.all(
        color: Colors.transparent,
        width: 1.0,
      ),
    );
  }


  static BoxDecoration dropDownDecorationError(BuildContext context) {
    return BoxDecoration(
      color: Colors.transparent,
      borderRadius: BorderRadius.circular(themeSizes.borderRadius),
      border: Border.all(
        color: ThemeProvider.of(context).appColors.error,
        width: 2.0,
      ),
    );
  }

  static BoxDecoration topBorder(BuildContext context) {
    return BoxDecoration(
      border: Border(
        top: BorderSide(
          color: ThemeProvider.of(context).themeColors.border,
          width: 1.0,
        ),
      ),
    );
  }

  static BoxDecoration notificationBox(BuildContext context) {
    return BoxDecoration(
      color: ThemeProvider.of(context).appColors.white,
      borderRadius: BorderRadius.circular(20), // Aqui arredonda as bordas
      border: Border(
        bottom: BorderSide(
          color: ThemeProvider.of(context).themeColors.border,
          width: 1.0,
        ),
      ),
    );
  }

  static BoxDecoration bottomBorder(BuildContext context) {
    return BoxDecoration(
      border: Border(
        bottom: BorderSide(
          color: ThemeProvider.of(context).themeColors.border,
          width: 1.0,
        ),
      ),
    );
  }
}