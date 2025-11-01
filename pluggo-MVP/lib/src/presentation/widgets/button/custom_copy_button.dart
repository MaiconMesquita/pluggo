import 'package:flutter/material.dart';
import 'package:flutter/services.dart'; // Para o Clipboard
import '../../../domain/enum/color_type.dart';
import '../../../presentation/provider/theme_provider.dart';
import '../../../presentation/styles/styles.dart';
import '../../styles/spacings.dart';
import '../../styles/typography.dart';

class CustomCopyButton extends StatelessWidget {
  final ColorType color;
  final bool border;
  final String text; // O texto que será copiado

  const CustomCopyButton({
    super.key,
    required this.color,
    this.border = false,
    required this.text,
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
        themeColor = Colors.white;
        onThemeColor = ThemeProvider.of(context).appColors.secondary;
        break;
    }

    return Focus(
      onFocusChange: (hasFocus) {
        FocusManager.instance.primaryFocus?.unfocus();
      },
      child: Container(
        padding: EdgeInsets.only(top: HeightSpacing.extraSmall),
        child: SizedBox(
          width: MediaQuery.of(context).size.width,
          height: HeightSpacing.heightBtn,
          child: ElevatedButton(
            key: Key(text),
            onPressed: () {
              // Copiar o texto para o clipboard
              Clipboard.setData(ClipboardData(text: text)).then((_) {
                // Exibir um snackbar ou algum feedback para o usuário
                ScaffoldMessenger.of(context).showSnackBar(
                    SnackBar(content: Text('Texto copiado!'))
                );
              });
            },
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
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween, // Distribui os itens com espaço entre
              children: [
                Expanded(
                  child: Text(
                    text,
                    style: AppTypography.button(context).copyWith(color: onThemeColor),
                    overflow: TextOverflow.ellipsis, // Adicionando o "..."
                    maxLines: 1, // Limitando a uma linha
                  ),
                ),
                SizedBox(width: 8), // Espaço entre o texto e o ícone
                IconButton(
                  icon: Icon(Icons.copy, color: onThemeColor),
                  onPressed: () {
                    // Copiar o texto para o clipboard
                    Clipboard.setData(ClipboardData(text: text)).then((_) {
                      // Exibir um snackbar ou algum feedback para o usuário
                      ScaffoldMessenger.of(context).showSnackBar(
                          SnackBar(content: Text('Texto copiado!'))
                      );
                    });
                  },
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
