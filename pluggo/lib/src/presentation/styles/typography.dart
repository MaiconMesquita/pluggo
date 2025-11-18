import '../../../utils/device_dimensions.dart';
import '../../config/theme.dart';
import '../provider/theme_provider.dart';
import 'package:flutter/material.dart';

/*
configurando a tipografia de acordo com as dimensões e falando quais as cores e valores a serem usados
 */

class AppTypography {
  static double responsiveFontSize(double fontSize) {
    final double screenWidth = DeviceDimensions.screenWidth;
    const double baseWidthResolution = 428.0;

    // Calcule o fator de escala com base na proporção da largura da tela para a largura base
    double scaleFactor = screenWidth / baseWidthResolution;

    // Garanta que o fator de escala esteja entre 0.7 e 1.3
    scaleFactor = scaleFactor.clamp(0.7, 1.3);

    return fontSize * scaleFactor;
  }

  static FontWeight getFontWeight(FontWeight fontWeight) {
    switch (fontWeight) {
      case FontWeight.w100:
        return FontWeight.w100;
      case FontWeight.w200:
        return FontWeight.w400;
      case FontWeight.w300:
        return FontWeight.w400;
      case FontWeight.w400:
        return FontWeight.w400;
      case FontWeight.w500:
        return themeNumber == 1 ? FontWeight.w500 : FontWeight.w700;
      case FontWeight.w600:
        return themeNumber == 1 ? FontWeight.w500 : FontWeight.w700;
      case FontWeight.w700:
        return FontWeight.w700;
      case FontWeight.w800:
        return FontWeight.w700;
      case FontWeight.w900:
        return FontWeight.w700;
      default:
        return FontWeight.w400;
    }
  }

  static TextStyle headline(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(24),
      fontFamily: ThemeProvider.of(context).typography.titleFontFamily,
      fontWeight: getFontWeight(FontWeight.w700),
      color: ThemeProvider.of(context).themeColors.primaryContainer,
    );
  }

  static TextStyle headlineWhite(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(24),
      fontFamily: ThemeProvider.of(context).typography.titleFontFamily,
      fontWeight: getFontWeight(FontWeight.w700),
      color: ThemeProvider.of(context).appColors.white,
    );
  }

  static TextStyle numericKeyboard(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.titleFontSize),
      fontFamily: 'Gilroy',
      fontWeight: getFontWeight(FontWeight.w700),
      color: ThemeProvider.of(context).themeColors.titleText,
    );
  }

  static TextStyle title(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.titleFontSize),
      fontFamily: ThemeProvider.of(context).typography.titleFontFamily,
      fontWeight: getFontWeight(FontWeight.w700),
      color: ThemeProvider.of(context).themeColors.titleText,
    );
  }

  static TextStyle whiteTitle(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.btnText),
      fontFamily: ThemeProvider.of(context).typography.titleFontFamily,
      fontWeight: getFontWeight(FontWeight.w700),
      color: ThemeProvider.of(context).themeColors.titleText,
    );
  }

  static TextStyle titleBalck(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.titleFontSize),
      fontFamily: ThemeProvider.of(context).typography.titleFontFamily,
      fontWeight: getFontWeight(FontWeight.w700),
      color: ThemeProvider.of(context).appColors.black,
    );
  }

  static TextStyle formsTitle(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.formsSubTitleFontSize),
      fontFamily: ThemeProvider.of(context).typography.titleFontFamily,
      fontWeight: getFontWeight(FontWeight.w700),
      color: ThemeProvider.of(context).themeColors.titleText,
    );
  }

  static TextStyle value(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.valueFontSize),
      fontFamily: ThemeProvider.of(context).typography.titleFontFamily,
      fontWeight: getFontWeight(FontWeight.w700),
      color: ThemeProvider.of(context).themeColors.titleText,
    );
  }

  static TextStyle subtitle(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.subtitleFontSize),
      fontFamily: ThemeProvider.of(context).typography.subtitleFontFamily,
      fontWeight: getFontWeight(FontWeight.w500),
      color: ThemeProvider.of(context).themeColors.subtitleText,
    );
  }

  static TextStyle formsSubtitle(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.subtitleFontSize),
      fontFamily: ThemeProvider.of(context).typography.subtitleFontFamily,
      fontWeight: getFontWeight(FontWeight.w300),
      color: ThemeProvider.of(context).themeColors.subtitleText,
    );
  }

  static TextStyle formsPurpleSubtitle(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.formsSubTitleFontSize),
      fontFamily: ThemeProvider.of(context).typography.subtitleFontFamily,
      fontWeight: getFontWeight(FontWeight.w300),
      color: ThemeProvider.of(context).themeColors.secondaryContainer,
    );
  }

  static TextStyle formsSubtitleWhite(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.formsSubTitleFontSize),
      fontFamily: ThemeProvider.of(context).typography.subtitleFontFamily,
      fontWeight: getFontWeight(FontWeight.w300),
      color: ThemeProvider.of(context).appColors.white,
    );
  }

  static TextStyle warningText(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.smallMedium),
      fontFamily: ThemeProvider.of(context).typography.subtitleFontFamily,
      fontWeight: getFontWeight(FontWeight.w300),
      color: ThemeProvider.of(context).appColors.white,
    );
  }

  static TextStyle formsSubtitleBold(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.formsSubTitleFontSize),
      fontFamily: ThemeProvider.of(context).typography.subtitleFontFamily,
      fontWeight: getFontWeight(FontWeight.w700),
      color: ThemeProvider.of(context).appColors.light,
    );
  }

  static TextStyle subtitleBold(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.subtitleFontSize),
      fontFamily: ThemeProvider.of(context).typography.subtitleFontFamily,
      fontWeight: getFontWeight(FontWeight.w700),
      color: ThemeProvider.of(context).themeColors.subtitleText,
    );
  }

  static TextStyle body(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.mediumFontSize),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w400),
      color: ThemeProvider.of(context).themeColors.body1Text,
    );
  }

  static TextStyle smallBodyBlack(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.smallMedium),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w400),
      color: ThemeProvider.of(context).appColors.black,
    );
  }

  static TextStyle bodyBlack(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.mediumFontSize),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w400),
      color: ThemeProvider.of(context).appColors.black,
    );
  }

  static TextStyle bodyTitlePrimary(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.subtitleFontSize),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w700),
      color: ThemeProvider.of(context).appColors.primary,
    );
  }

  static TextStyle bodyTitleBlack(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.subtitleFontSize),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w700),
      color: ThemeProvider.of(context).appColors.black,
    );
  }

  static TextStyle bodyBigger(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.medium),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w400),
      color: ThemeProvider.of(context).appColors.primary,
    );
  }

  static TextStyle bodyBiggerWhite(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.medium),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w400),
      color: ThemeProvider.of(context).appColors.white,
    );
  }

  static TextStyle bodyIcon(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.mediumFontSize),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w400),
      color: ThemeProvider.of(context).appColors.primary,
    );
  }

  static TextStyle bodyIconWhite(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.medium),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w400),
      color: ThemeProvider.of(context).appColors.white,
    );
  }

  static TextStyle bodyBold(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.formsSubTitleFontSize),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w700),
      color: ThemeProvider.of(context).themeColors.primaryContainer,
    );
  }

  static TextStyle bodyBoldSecondary(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.formsSubTitleFontSize),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w700),
      color: ThemeProvider.of(context).appColors.light,
    );
  }

  static TextStyle bodyBoldWhite(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.formsSubTitleFontSize),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w700),
      color: ThemeProvider.of(context).appColors.white,
    );
  }

  static TextStyle bodyWhite(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.formsSubTitleFontSize),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w200),
      color: ThemeProvider.of(context).appColors.white,
    );
  }

  static TextStyle description(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.smallMedium),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w400),
      color: ThemeProvider.of(context).appColors.grey700,
    );
  }

  static TextStyle descriptionWhite(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.smallFontSize),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w400),
      color: ThemeProvider.of(context).appColors.white,
    );
  }

  static TextStyle button(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.btnText),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      //fontWeight: getFontWeight(themeNumber == 1 ? FontWeight.w400 : FontWeight.w400),
      fontWeight: FontWeight.w400,
      color: ThemeProvider.of(context).themeColors.onPrimaryContainer,
    );
  }

  static TextStyle iconButton(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.smallFontSize),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      //fontWeight: getFontWeight(themeNumber == 1 ? FontWeight.w400 : FontWeight.w400),
      fontWeight: FontWeight.w600,
      color: ThemeProvider.of(context).themeColors.onPrimaryContainer,
    );
  }

  static TextStyle caption(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.smallFontSize),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w400),
      color: ThemeProvider.of(context).themeColors.body1Text,
    );
  }

  static TextStyle infoTitle(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.medium),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w500),
      color: ThemeProvider.of(context).appColors.primary,
    );
  }

  static TextStyle infoLabel(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.medium),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w400),
      color: ThemeProvider.of(context).themeColors.body1Text,
    );
  }

  static TextStyle infoItem(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.mediumFontSize),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w300),
      color: ThemeProvider.of(context).appColors.white,
    );
  }
  static TextStyle infoItemBrands(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.formsSubTitleFontSize),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w300),
      color: ThemeProvider.of(context).appColors.primary,
    );
  }

  static TextStyle infoItemBrandsWhite(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.formsSubTitleFontSize),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w300),
      color: ThemeProvider.of(context).appColors.white,
    );
  }

  static TextStyle infoTitleSmall(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.smallMedium),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w500),
      color: ThemeProvider.of(context).themeColors.body1Text,
    );
  }

  static TextStyle popUpDesc(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.smallMedium),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w500),
      color: ThemeProvider.of(context).appColors.white,
    );
  }

  static TextStyle infoTitleSmallGray(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.smallMedium),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w500),
      color: ThemeProvider.of(context).themeColors.primaryContainer,
    );
  }

  static TextStyle infoLabelSmall(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.smallFontSize),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w600),
      color: ThemeProvider.of(context).themeColors.body1Text,
    );
  }

  static TextStyle infoLabelSmallPressed(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.smallFontSize),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w700),
      color: ThemeProvider.of(context).themeColors.primaryContainer,
    );
  }

  static TextStyle infoItemSmall(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.smallFontSize),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w400),
      color: ThemeProvider.of(context).themeColors.body1Text,
    );
  }

  static TextStyle inputLabel(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.mediumFontSize),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w500),
      color: ThemeProvider.of(context).themeColors.inputText,
    );
  }

  static TextStyle inputError(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.smallFontSize),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w400),
      color: ThemeProvider.of(context).appColors.error,
      height: 0.75,
    );
  }

  static TextStyle titleInBox(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.btnText),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(themeNumber == 1 ? FontWeight.w500 : FontWeight.w400),
      color: ThemeProvider.of(context).themeColors.inputHint,
    );
  }

  static TextStyle inputHint(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.btnText),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(themeNumber == 1 ? FontWeight.w500 : FontWeight.w400),
      color: ThemeProvider.of(context).themeColors.inputHint.withOpacity(0.5),
    );
  }

  static TextStyle newInputHintForDark(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.btnText),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(themeNumber == 1 ? FontWeight.w500 : FontWeight.w400),
      color: ThemeProvider.of(context).appColors.white.withOpacity(0.5),
    );
  }

  static TextStyle newInputHintForBright(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.btnText),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(themeNumber == 1 ? FontWeight.w300 : FontWeight.w200),
      color: ThemeProvider.of(context).appColors.primary.withOpacity(0.5),
    );
  }

  static TextStyle inputText(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.btnText),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w400),
      color: ThemeProvider.of(context).themeColors.inputText,
    );
  }

  static TextStyle newInputTextForDark(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.btnText),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w400),
      color: ThemeProvider.of(context).appColors.secondary,
    );
  }

  static TextStyle newInputTextForBright(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.btnText),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w400),
      color: ThemeProvider.of(context).appColors.white,
    );
  }

  static TextStyle appBarTitle(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.titleFontSize),
      fontFamily: ThemeProvider.of(context).typography.titleFontFamily,
      fontWeight: getFontWeight(FontWeight.w700),
      color: ThemeProvider.of(context).appColors.primary,
    );
  }

  static TextStyle appBarSubtitle(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.smallFontSize),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w700),
      color: ThemeProvider.of(context).themeColors.appBarSubtitle,
    );
  }

  static TextStyle popUpDescription(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.subtitleFontSize),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w400),
      color: ThemeProvider.of(context).themeColors.body1Text,
    );
  }

  static TextStyle popUpWarningText(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.small),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w400),
      color: ThemeProvider.of(context).themeColors.body1Text,
    );
  }

  static TextStyle token(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.subtitleFontSize),
      fontFamily: ThemeProvider.of(context).typography.tokenFontFamily,
      fontWeight: getFontWeight(FontWeight.w300),
      color: ThemeProvider.of(context).appColors.white,
    );
  }

  static TextStyle loginNames(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.titleFontSize),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w500),
      color: ThemeProvider.of(context).themeColors.body1Text,
    );
  }

  static TextStyle loginGreetings(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.loginGreeting),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w500),
      color: ThemeProvider.of(context).appColors.white,
      height: 1.2,
    );
  }

  static TextStyle loginName(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.loginName),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w700),
      color: ThemeProvider.of(context).appColors.white,
    );
  }

  static TextStyle newTitle(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.newTitle),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w700),
      color: ThemeProvider.of(context).appColors.white,
    );
  }

  static TextStyle greetingName(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(ThemeProvider.of(context).sizes.loginName),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w100),
      color: ThemeProvider.of(context).appColors.white,
    );
  }

  static TextStyle loginGreetings2(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(48),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w400),
      color: ThemeProvider.of(context).themeColors.body1Text,
    );
  }

  static TextStyle popUpTitle(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(40),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w600),
      color: ThemeProvider.of(context).themeColors.titleText,
    );
  }

  static TextStyle popUpInputTitle(BuildContext context) {
    return TextStyle(
      fontSize: responsiveFontSize(25),
      fontFamily: ThemeProvider.of(context).typography.bodyFontFamily,
      fontWeight: getFontWeight(FontWeight.w600),
      color: ThemeProvider.of(context).themeColors.titleText,
    );
  }
}