import '../../../domain/enum/color_type.dart';
import '../../../presentation/provider/theme_provider.dart';
import '../../../presentation/widgets/button/custom_rounded_loading_button.dart';
import 'package:flutter/material.dart';
import '../../../presentation/styles/styles.dart';
import '../../styles/spacings.dart';
import '../../styles/typography.dart';

class CustomRoundedLoadingButton extends StatelessWidget {
  final CustomLoadingButtonController? controller;
  final ColorType color;
  final bool border;
  final VoidCallback? onPressedCallback;
  final String text;
  final bool Function() isValid;

  const CustomRoundedLoadingButton(
      {super.key,
        this.controller,
        required this.color,
        this.border = false,
        required this.onPressedCallback,
        required this.text,
        required this.isValid});

  @override
  Widget build(BuildContext context) {
    Color themeColor;
    Color onThemeColor;
    switch (color) {
      case ColorType.primary://tipo de cor do fundo do botão escolhida
        themeColor = ThemeProvider.of(context).themeColors.primaryContainer; //fundo do botão
        onThemeColor = ThemeProvider.of(context).themeColors.onPrimaryContainer; //cor do texto
        break;
      case ColorType.secondary:
        themeColor = ThemeProvider.of(context).themeColors.secondaryContainer;
        onThemeColor = ThemeProvider.of(context).appColors.secondary; //cor do texto
        break;
      case ColorType.tertiary:
        themeColor = ThemeProvider.of(context).themeColors.tertiaryContainer;
        onThemeColor = ThemeProvider.of(context).themeColors.onTertiaryContainer;
        break;
      case ColorType.background:
        themeColor = ThemeProvider.of(context).themeColors.background;
        onThemeColor = ThemeProvider.of(context).themeColors.background;
        break;
      case ColorType.transparent:
        themeColor = Colors.transparent;
        onThemeColor = ThemeProvider.of(context).appColors.white;
        break;
      case ColorType.white:
        themeColor = Colors.white;
        onThemeColor = ThemeProvider.of(context).appColors.secondary;
        break;
    }

    return Focus(
      onFocusChange: (hasFocus) {
        FocusManager.instance.primaryFocus?.unfocus();
      },
      child: controller != null
          ? Container(
        padding: EdgeInsets.only(top: HeightSpacing.extraSmall, bottom: HeightSpacing.extraSmall),
        child: CustomLoadingButton(
            key: Key(text),
            animateOnTap: false,
            width: MediaQuery.of(context).size.width,
            height: HeightSpacing.heightBtn,
            controller: controller!,
            onPressed: onPressedCallback != null
                ? () {
              if (isValid()) {
                if (controller?.currentState != ButtonState.idle) return;
                controller?.start();
                FocusManager.instance.primaryFocus?.unfocus();
                onPressedCallback?.call();
              }
            }
                : null,
            borderRadius: themeSizes.borderRadius,
            color: themeColor,
            valueColor: onThemeColor,
            foregroundColor: onThemeColor,
            successColor: themeColor,
            child: Text(text, style: AppTypography.button(context).copyWith(color: onThemeColor))),
      )
          : Container(
        padding: EdgeInsets.only(top: HeightSpacing.extraSmall),
        child: SizedBox(
          width: MediaQuery.of(context).size.width,
          height: HeightSpacing.heightBtn,
          child: ElevatedButton(
            key: Key(text),
            onPressed: onPressedCallback != null
                ? () {
              if (isValid()) {
                FocusManager.instance.primaryFocus?.unfocus();
                onPressedCallback?.call();
              }
            }
                : null,
            style: ElevatedButton.styleFrom(
              foregroundColor: onThemeColor.withOpacity(0.5),
              shadowColor: Colors.transparent,
              backgroundColor: themeColor,
              elevation: 0,
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(themeSizes.borderRadius),
              ),
              side: border ? BorderSide(color: onThemeColor, width: 1.0) : null,
            ),
            child: Text(
              text,
              style: AppTypography.button(context).copyWith(color: onThemeColor),
            ),
          ),
        ),
      ),
    );
  }
}