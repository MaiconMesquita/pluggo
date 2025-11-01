import 'package:flutter/material.dart';

import '../../provider/theme_provider.dart';
import '../../styles/decoration.dart';
import '../../styles/spacings.dart';
import '../../styles/typography.dart';

class HorizontalToggleWidget extends StatelessWidget {
  final String title;
  final List<HorizontalSelectOption> options;
  final String? selectedValue;
  final bool showError;
  final Function(String)? onOptionSelected;

  const HorizontalToggleWidget({
    super.key,
    required this.title,
    required this.options,
    this.selectedValue,
    this.showError = false,
    this.onOptionSelected,
  });

  @override
  Widget build(BuildContext context) {
    final primary = ThemeProvider.of(context).appColors.primary;
    final grey = Colors.grey.shade600;

    return Container(
      padding: EdgeInsets.only(
        bottom: showError ? 0 : HeightSpacing.small,
        top: HeightSpacing.small,
      ),
      child: Column(
        children: [
          Container(
            alignment: Alignment.bottomCenter,
            padding: EdgeInsets.symmetric(vertical: HeightSpacing.small),
            decoration: showError
                ? AppDecoration.radiusBoxDecorationError(context)
                : AppDecoration.radiusBoxDecoration(context),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                /// Toggle
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                  children: options.map((option) {
                    final isSelected = option.value == selectedValue;

                    return GestureDetector(
                      onTap: () => onOptionSelected?.call(option.value),
                      child: Container(
                        padding:
                        const EdgeInsets.symmetric(vertical: 10, horizontal: 14),
                        decoration: BoxDecoration(
                          borderRadius: BorderRadius.circular(30),
                        ),
                        child: Row(
                          children: [
                            // Bolinha com check
                            Container(
                              width: 18,
                              height: 18,
                              decoration: isSelected
                                  ? BoxDecoration(
                                shape: BoxShape.circle,
                                color: Colors.white ,
                              ) : null ,
                              child: isSelected
                                  ? Icon(Icons.check, size: 14, color: primary)
                                  : null,
                            ),
                            const SizedBox(width: 6),

                            // Texto
                            Text(
                              option.label,
                              style: AppTypography.bodyTitlePrimary(context).copyWith(
                                color: isSelected ? Colors.white : grey,
                                fontWeight:
                                isSelected ? FontWeight.bold : FontWeight.normal,
                              ),
                            ),
                          ],
                        ),
                      ),
                    );
                  }).toList(),
                ),
              ],
            ),
          ),

          if (showError)
            Padding(
              padding: const EdgeInsets.only(top: 8, left: 20),
              child: Text(
                'Por favor, selecione uma opção.',
                style: AppTypography.inputError(context),
              ),
            ),
        ],
      ),
    );
  }
}

class HorizontalSelectOption {
  final String label;
  final String value;

  const HorizontalSelectOption({
    required this.label,
    required this.value,
  });
}
