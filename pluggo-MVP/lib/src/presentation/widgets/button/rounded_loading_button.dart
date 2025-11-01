import '../../../domain/enum/color_type.dart';
import '../../../presentation/provider/theme_provider.dart';
import '../../../presentation/widgets/button/custom_rounded_loading_button.dart';
import 'package:flutter/material.dart';
import '../../../presentation/styles/styles.dart';
import '../../styles/spacings.dart';
import '../../styles/typography.dart';

class roundedLoadingButton extends StatelessWidget {
  final CustomLoadingButtonController? controller;
  final ColorType color;
  final bool border;
  final VoidCallback? onPressedCallback;
  final String? text; // Agora opcional
  final String? iconPath; // Novo parâmetro para o ícone
  final bool Function() isValid;

  const roundedLoadingButton({
    super.key,
    this.controller,
    required this.color,
    this.border = false,
    required this.onPressedCallback,
    this.text, // Agora opcional
    this.iconPath, // Novo parâmetro
    required this.isValid,
  });

  @override
  Widget build(BuildContext context) {
    Color themeColor;
    Color onThemeColor;
    switch (color) {
      case ColorType.primary:
        themeColor = ThemeProvider.of(context).themeColors.primaryContainer;
        onThemeColor = ThemeProvider.of(context).themeColors.onPrimaryContainer;
        break;
      case ColorType.secondary:
        themeColor = ThemeProvider.of(context).themeColors.secondaryContainer;
        onThemeColor = ThemeProvider.of(context).appColors.secondary;
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
        themeColor = Colors.transparent;
        onThemeColor = ThemeProvider.of(context).appColors.white;
        break;
    }

    double buttonSize = HeightSpacing.custom(75); // Ajuste o tamanho conforme necessário

    return Focus(
      onFocusChange: (hasFocus) {
        FocusManager.instance.primaryFocus?.unfocus();
      },
      child: controller != null
          ? Container(
        padding: EdgeInsets.only(top: HeightSpacing.small, bottom: HeightSpacing.small),
        child: CustomLoadingButton(
          key: Key(text ?? 'icon_button'), // Mudado para aceitar o ícone
          animateOnTap: false,
          width: buttonSize,
          height: buttonSize,
          controller: controller!,
          onPressed: onPressedCallback != null
              ? () {
            if (isValid()) {
              if (controller?.currentState != ButtonState.idle) return;
              controller?.start(); //aqui começa o loading e aí no provider para ou reseta
              FocusManager.instance.primaryFocus?.unfocus();
              onPressedCallback?.call();
            }
          }
              : null,
          borderRadius: buttonSize / 2,
          color: themeColor,
          valueColor: onThemeColor,
          foregroundColor: onThemeColor,
          successColor: themeColor,
          child: iconPath != null
              ? Image.asset(iconPath!) // Exibe o ícone
              : Text(
            text ?? '',
            style: AppTypography.button(context).copyWith(color: onThemeColor),
          ),
        ),
      )
          : Container( //sem controller ´r só um elevated button
        padding: EdgeInsets.only(top: HeightSpacing.extraSmall),
        child: SizedBox(
          width: buttonSize,
          height: buttonSize,
          child: ElevatedButton(
            key: Key(text ?? 'icon_button'), // Mudado para aceitar o ícone
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
                borderRadius: BorderRadius.circular(50),
              ),
              side: border ? BorderSide(color: onThemeColor, width: 1.0) : null,
            ),
            child: iconPath != null
                ? Image.asset(iconPath!) // Exibe o ícone
                : Text(
              text ?? '',
              style: AppTypography.button(context).copyWith(color: onThemeColor),
            ),
          ),
        ),
      ),
    );
  }
}
