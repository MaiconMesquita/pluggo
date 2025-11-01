import '../../presentation/provider/theme_provider.dart';
import '../../presentation/styles/styles.dart';
import 'package:flutter/material.dart';

class AppBorders {
  static inputBorderGray(BuildContext context) {
    return OutlineInputBorder(
      borderRadius: BorderRadius.all(Radius.circular(themeSizes.borderRadius)),
      borderSide: BorderSide(color: ThemeProvider.of(context).themeColors.border, width: themeSizes.inputBorderSize),
    );
  }

  static focusedInputBorderGray(BuildContext context) {
    return OutlineInputBorder(
      borderRadius: BorderRadius.all(Radius.circular(themeSizes.borderRadius)),
      borderSide: BorderSide(color: ThemeProvider.of(context).themeColors.border, width: 2),
    );
  }

  static inputBorderBlack(BuildContext context) {
    return OutlineInputBorder(
      borderRadius: BorderRadius.all(Radius.circular(themeSizes.borderRadius)),
      borderSide: BorderSide(color: ThemeProvider.of(context).themeColors.body1Text, width: themeSizes.inputBorderSize),
    );
  }

  static focusedInputBorderBlack(BuildContext context) {
    return OutlineInputBorder(
      borderRadius: BorderRadius.all(Radius.circular(themeSizes.borderRadius)),
      borderSide: BorderSide(color: ThemeProvider.of(context).themeColors.body1Text, width: 2),
    );
  }

  static inputBorderWhite(BuildContext context) {
    return OutlineInputBorder(
      borderRadius: BorderRadius.all(Radius.circular(themeSizes.borderRadius)),
      borderSide: BorderSide(color: ThemeProvider.of(context).themeColors.background, width: themeSizes.inputBorderSize),
    );
  }

  static inputBorderPrimary(BuildContext context) {
    return OutlineInputBorder(
      borderRadius: BorderRadius.all(Radius.circular(themeSizes.borderRadius)),
      borderSide: BorderSide(color: ThemeProvider.of(context).appColors.primary, width: themeSizes.inputBorderSize),
    );
  }

  static focusedInputBorderDark(BuildContext context) {
    return OutlineInputBorder(
      borderRadius: BorderRadius.all(Radius.circular(themeSizes.borderRadius)),
      borderSide: BorderSide(color: ThemeProvider.of(context).themeColors.primaryContainer, width: 2),
    );
  }

  static focusedInputBorder(BuildContext context) {
    return OutlineInputBorder(
      borderRadius: BorderRadius.all(Radius.circular(themeSizes.borderRadius)),
      borderSide: BorderSide(color: ThemeProvider.of(context).themeColors.border, width: 2),
    );
  }

  static inputBorderError(BuildContext context) {
    return OutlineInputBorder(
      borderRadius: BorderRadius.all(Radius.circular(themeSizes.borderRadius)),
      borderSide: BorderSide(color: ThemeProvider.of(context).appColors.error, width: 2),
    );
  }

  static focusedInputBorderError(BuildContext context) {
    return OutlineInputBorder(
      borderRadius: BorderRadius.all(Radius.circular(themeSizes.borderRadius)),
      borderSide: BorderSide(color: ThemeProvider.of(context).appColors.error, width: 2),
    );
  }
}