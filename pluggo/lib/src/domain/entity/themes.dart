/*
Esse código define uma estrutura de temas personalizados para um aplicativo Flutter,
 permitindo que você gerencie cores, tamanhos, tipografia e outros aspectos
  visuais de forma consistente em toda a aplicação
 */

import 'package:flutter/material.dart';

class CustomTheme {
  final AppColors appColors;
  final ThemeColors themeColors;
  final ThemeSizes sizes;
  final ThemeTypography typography;
  final bool hasFooter;
  final bool alwaysShowLoginBackground;
  final bool hasInputLabel;
  final bool hasInputPrefix;

  CustomTheme({
    required this.appColors,
    required this.themeColors,
    required this.sizes,
    required this.typography,
    required this.hasFooter,
    required this.alwaysShowLoginBackground,
    required this.hasInputLabel,
    required this.hasInputPrefix,
  });
}

class AppColors {
  final Color primary;
  final Color secondary;
  final Color light;
  final Color beautyCard;
  final Color foodCard;
  final Color carCard;
  final Color error;
  final Color success;
  final Color black;
  final Color white;
  final Color grey100;
  final Color grey200;
  final Color grey300;
  final Color grey400;
  final Color grey500;
  final Color grey600;
  final Color grey700;
  final Color grey800;
  final Color grey900;
  final Color greyDark;

  AppColors({
    required this.primary,
    required this.secondary,
    required this.light,
    required this.beautyCard,
    required this.foodCard,
    required this.carCard,
    required this.error,
    required this.success,
    required this.black,
    required this.white,
    required this.grey100,
    required this.grey200,
    required this.grey300,
    required this.grey400,
    required this.grey500,
    required this.grey600,
    required this.grey700,
    required this.grey800,
    required this.grey900,
    required this.greyDark,
  });
}

class ThemeTypography {
  final String bodyFontFamily;
  final String titleFontFamily;
  final String subtitleFontFamily;
  final String tokenFontFamily;

  ThemeTypography({
    required this.bodyFontFamily,
    required this.titleFontFamily,
    required this.subtitleFontFamily,
    required this.tokenFontFamily,
  });
}

class ThemeSizes {
  final double borderRadius;
  final double inputBorderSize;
  final double loginRadius;
  final double numericKeyboardRadius;
  final double elevation;
  final double iconSize;
  final double bottomIconSize;
  final double iconSizeHeader;
  final double extraSmall;
  final double small;
  final double smallMedium;
  final double medium;
  final double mediumLarge;
  final double large;
  final double valueFontSize;
  final double subtitleFontSize;
  final double titleFontSize;
  final double formsSubTitleFontSize;
  final double loginName;
  final double newTitle;
  final double mediumFontSize;
  final double smallFontSize;
  final double buttonHeight;
  final double inputHeight;
  final double minFontScaleFactor;
  final double maxFontScaleFactor;
  final double baseScreenWidth;
  final double baseScreenHeight;
  final double loginGreeting;
  final double btnText;


  ThemeSizes({
    required this.borderRadius,
    required this.inputBorderSize,
    required this.loginRadius,
    required this.numericKeyboardRadius,
    required this.elevation,
    required this.iconSize,
    required this.bottomIconSize,
    required this.iconSizeHeader,
    required this.extraSmall,
    required this.small,
    required this.smallMedium,
    required this.medium,
    required this.mediumLarge,
    required this.large,
    required this.newTitle,
    required this.valueFontSize,
    required this.titleFontSize,
    required this.formsSubTitleFontSize,
    required this.loginName,
    required this.subtitleFontSize,
    required this.mediumFontSize,
    required this.smallFontSize,
    required this.buttonHeight,
    required this.inputHeight,
    required this.minFontScaleFactor,
    required this.maxFontScaleFactor,
    required this.baseScreenWidth,
    required this.baseScreenHeight,
    required this.loginGreeting,
    required this.btnText,

  });
}

class ThemeColors {
  final Brightness brightness;
  final Color primaryContainer;
  final Color onPrimaryContainer;
  final Color secondaryContainer;
  final Color onSecondaryContainer;
  final Color tertiaryContainer;
  final Color onTertiaryContainer;
  final Color background;
  final Color onBackground;
  final Color avatarBackground;
  final Color titleText;
  final Color subtitleText;
  final Color body1Text;
  final Color body2Text;
  final Color body3Text;
  final Color body4Text;
  final Color appBarTitle;
  final Color appBarSubtitle;
  final Color icon;
  final Color action;
  final Color border;
  final Color inputText;
  final Color inputLabel;
  final Color inputHint;
  final Color textButton;
  final Color inputBackground;
  final Color shimmerEffect;
  final Color disabled;

  ThemeColors({
    required this.brightness,
    required this.primaryContainer,
    required this.onPrimaryContainer,
    required this.secondaryContainer,
    required this.onSecondaryContainer,
    required this.tertiaryContainer,
    required this.onTertiaryContainer,
    required this.background,
    required this.onBackground,
    required this.avatarBackground,
    required this.titleText,
    required this.subtitleText,
    required this.body1Text,
    required this.body2Text,
    required this.body3Text,
    required this.body4Text,
    required this.appBarTitle,
    required this.appBarSubtitle,
    required this.icon,
    required this.action,
    required this.border,
    required this.inputText,
    required this.inputLabel,
    required this.inputHint,
    required this.textButton,
    required this.inputBackground,
    required this.shimmerEffect,
    required this.disabled,
  });

  ThemeColors copyWith({
    Brightness? brightness,
    Color? primaryContainer,
    Color? onPrimaryContainer,
    Color? secondaryContainer,
    Color? onSecondaryContainer,
    Color? tertiaryContainer,
    Color? onTertiaryContainer,
    Color? background,
    Color? onBackground,
    Color? avatarBackground,
    Color? titleText,
    Color? subtitleText,
    Color? body1Text,
    Color? body2Text,
    Color? body3Text,
    Color? body4Text,
    Color? appBarTitle,
    Color? appBarSubtitle,
    Color? icon,
    Color? action,
    Color? border,
    Color? inputText,
    Color? inputLabel,
    Color? inputHint,
    Color? textButton,
    Color? inputBackground,
    Color? shimmerEffect,
    Color? disabled,
  }) {
    return ThemeColors(
      brightness: brightness ?? this.brightness,
      primaryContainer: primaryContainer ?? this.primaryContainer,
      onPrimaryContainer: onPrimaryContainer ?? this.onPrimaryContainer,
      secondaryContainer: secondaryContainer ?? this.secondaryContainer,
      onSecondaryContainer: onSecondaryContainer ?? this.onSecondaryContainer,
      tertiaryContainer: tertiaryContainer ?? this.tertiaryContainer,
      onTertiaryContainer: onTertiaryContainer ?? this.onTertiaryContainer,
      background: background ?? this.background,
      onBackground: onBackground ?? this.onBackground,
      avatarBackground: avatarBackground ?? this.avatarBackground,
      titleText: titleText ?? this.titleText,
      subtitleText: subtitleText ?? this.subtitleText,
      body1Text: body1Text ?? this.body1Text,
      body2Text: body2Text ?? this.body2Text,
      body3Text: body3Text ?? this.body3Text,
      body4Text: body4Text ?? this.body4Text,
      appBarTitle: appBarTitle ?? this.appBarTitle,
      appBarSubtitle: appBarSubtitle ?? this.appBarSubtitle,
      icon: icon ?? this.icon,
      action: action ?? this.action,
      border: border ?? this.border,
      inputText: inputText ?? this.inputText,
      inputLabel: inputLabel ?? this.inputLabel,
      inputHint: inputHint ?? this.inputHint,
      textButton: textButton ?? this.textButton,
      inputBackground: inputBackground ?? this.inputBackground,
      shimmerEffect: shimmerEffect ?? this.shimmerEffect,
      disabled: disabled ?? this.disabled,
    );
  }
}