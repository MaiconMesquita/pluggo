import '../domain/entity/themes.dart';
import 'package:flutter/material.dart';

AppColors get appColors => AppColors(
  primary: const Color.fromRGBO(67, 91, 163, 1),
  secondary: const Color.fromRGBO(215, 107, 121, 1),
  light: const Color.fromRGBO(163, 138, 221, 1),
  beautyCard: const Color.fromRGBO(215, 107, 121, 1),
  foodCard: const Color.fromRGBO(196, 126, 0, 1),
  carCard: const Color.fromRGBO(67, 91, 163, 1),
  error: const Color.fromRGBO(243, 64, 64, 1),
  success: const Color.fromRGBO(92, 162, 81, 1),
  black: const Color.fromRGBO(10, 10, 10, 1),
  white: const Color.fromRGBO(248, 248, 248, 1),
  grey100: const Color.fromRGBO(241, 241, 241, 1),
  grey200: const Color.fromRGBO(234, 234, 234, 1),
  grey300: const Color.fromRGBO(225, 225, 225, 1),
  grey400: const Color.fromRGBO(219, 218, 218, 1),
  grey500: const Color.fromRGBO(210, 209, 209, 1),
  grey600: const Color.fromRGBO(191, 190, 190, 1),
  grey700: const Color.fromRGBO(149, 148, 148, 1),
  grey800: const Color.fromRGBO(116, 115, 115, 1),
  grey900: const Color.fromRGBO(88, 88, 88, 1),
  greyDark: const Color.fromRGBO(57, 57, 57, 1),
);

ThemeColors get defaultColors => ThemeColors(
  brightness: Brightness.dark,
  primaryContainer: appColors.primary,
  onPrimaryContainer: appColors.white,
  secondaryContainer: appColors.white,
  onSecondaryContainer: appColors.primary,
  tertiaryContainer: appColors.primary,
  onTertiaryContainer: appColors.white,
  background: appColors.white,
  onBackground: appColors.black,
  avatarBackground: const Color.fromRGBO(217, 217, 217, 1),
  appBarTitle: appColors.primary,
  appBarSubtitle: appColors.grey800,
  titleText: appColors.black,
  subtitleText: appColors.black,
  body1Text: appColors.grey800,
  body2Text: appColors.grey700,
  body3Text: appColors.grey600,
  body4Text: appColors.grey500,
  icon: appColors.white,
  action: appColors.black,
  border: appColors.white,
  inputText: appColors.white,
  inputLabel: appColors.black,
  inputHint: appColors.white,
  inputBackground: appColors.white,
  shimmerEffect: const Color.fromRGBO(175, 175, 175, 1),
  textButton: Colors.blue,
  disabled: const Color.fromRGBO(219, 218, 218, 0.5),
);

ThemeColors get secondaryColors => ThemeColors(
  brightness: Brightness.light,
  primaryContainer: appColors.primary,
  onPrimaryContainer: appColors.white,
  secondaryContainer: appColors.secondary,
  onSecondaryContainer: appColors.white,
  tertiaryContainer: appColors.black,
  onTertiaryContainer: appColors.primary,
  background: appColors.primary,
  onBackground: appColors.white,
  avatarBackground: defaultColors.avatarBackground,
  appBarTitle: appColors.white,
  appBarSubtitle: appColors.primary,
  titleText: appColors.white,
  subtitleText: appColors.white,
  body1Text: appColors.white,
  body2Text: defaultColors.body2Text,
  body3Text: defaultColors.body3Text,
  body4Text: defaultColors.body4Text,
  icon: appColors.white,
  action: appColors.white,
  border: appColors.secondary,
  inputText: appColors.secondary,
  inputLabel: appColors.white,
  inputHint: appColors.primary,
  inputBackground: defaultColors.inputBackground,
  shimmerEffect: defaultColors.shimmerEffect,
  textButton: defaultColors.textButton,
  disabled: defaultColors.disabled,
);

ThemeColors get loginColors => defaultColors.copyWith(
  background: const Color.fromRGBO(248, 248, 248, 0.9),
);

ThemeColors get menuColors => secondaryColors.copyWith(
  primaryContainer: appColors.black,
  onPrimaryContainer: appColors.white,
  secondaryContainer: appColors.primary,
  onSecondaryContainer: appColors.white,
  appBarTitle: appColors.white,
  appBarSubtitle: appColors.primary,
  body1Text: appColors.grey200,
  action: appColors.grey200,
  icon: appColors.grey200,
);

ThemeColors get mainColors => defaultColors.copyWith(
  primaryContainer: appColors.black,
  onPrimaryContainer: appColors.white,
  secondaryContainer: appColors.primary,
  onSecondaryContainer: appColors.white,
);

ThemeColors get valueColors => secondaryColors.copyWith(
  brightness: Brightness.light,
  primaryContainer: appColors.primary,
  onPrimaryContainer: appColors.white,
  secondaryContainer: appColors.black,
  onSecondaryContainer: appColors.white,
  tertiaryContainer: appColors.black,
  onTertiaryContainer: appColors.primary,
  titleText: appColors.white,
);

ThemeColors get statementColors => secondaryColors.copyWith(
  primaryContainer: const Color.fromRGBO(38, 38, 30, 1),
  onPrimaryContainer: appColors.white,
  secondaryContainer: const Color.fromRGBO(38, 38, 30, 1),
  onSecondaryContainer: appColors.white,
  tertiaryContainer: const Color.fromRGBO(38, 38, 30, 1),
  onTertiaryContainer: appColors.white,
  appBarTitle: appColors.primary,
  appBarSubtitle: appColors.white,
);

ThemeColors get signupColors => secondaryColors.copyWith();

ThemeColors get cameraColors => secondaryColors.copyWith();

List<Color> cardsColors = [
  Color.fromRGBO(215, 107, 121, 1),
  Color.fromRGBO(196, 126, 0, 1),
  Color.fromRGBO(67, 91, 163, 1),
];