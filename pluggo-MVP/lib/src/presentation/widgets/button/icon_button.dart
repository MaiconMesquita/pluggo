import '../../provider/theme_provider.dart';
import '../../styles/spacings.dart';
import '../../styles/styles.dart';
import 'package:flutter/material.dart';

class SvgIconButton extends StatelessWidget {
  const SvgIconButton({
    super.key,
    required this.iconPath,
    required this.onPressed,
    this.color,
    this.padding,
    this.size,
    this.showEffect,
    this.text, // Adiciona um novo parâmetro para o texto
    this.textStyle, // Adiciona um novo parâmetro para o estilo do texto
  });

  final String iconPath;
  final VoidCallback onPressed;
  final Color? color;
  final EdgeInsets? padding;
  final double? size;
  final bool? showEffect;
  final String? text; // Texto opcional
  final TextStyle? textStyle; // Estilo do texto opcional

  @override
  Widget build(BuildContext context) {
    return IconButton(
      padding: padding ?? EdgeInsets.zero,
      visualDensity: VisualDensity.compact,
      constraints: const BoxConstraints(),
      splashColor: showEffect != false ? null : Colors.transparent,
      highlightColor: showEffect != false ? null : Colors.transparent,
      iconSize: HeightSpacing.custom((size ?? 9) / 2) + (size ?? 9) / 2,
      icon: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Image.asset(
            iconPath,
            width: HeightSpacing.custom((size ?? 9) / 2) + (size ?? 9) / 2,
            height: HeightSpacing.custom((size ?? 9) / 2) + (size ?? 9) / 2,
            color: color,
          ),
          if (text != null) ...[
            SizedBox(width: 8), // Espaço entre o ícone e o texto
            Text(
              text!,
              style: textStyle ?? Theme.of(context).textTheme.bodyMedium, // Usa o estilo do texto padrão, se não for fornecido
            ),
          ],
        ],
      ),
      onPressed: onPressed,
    );
  }
}
