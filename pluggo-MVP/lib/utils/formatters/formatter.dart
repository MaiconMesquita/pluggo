import 'package:flutter/services.dart';
import 'package:mask_text_input_formatter/mask_text_input_formatter.dart';

class Formatter {
  static String removeNonNumbers(String value) {
    return value.replaceAll(RegExp(r'[^0-9]'), '');
  }

  static String removeNonAlphanumeric(String value) {
    return value.replaceAll(RegExp(r'[^a-zA-Z0-9]'), '');
  }

  static String formatCardNumber(String value) {
    // Remove tudo que não for número
    value = value.replaceAll(RegExp(r'[^0-9]'), '');

    // Limita o número de caracteres para 16 (tamanho do cartão de crédito padrão)
    if (value.length > 16) {
      value = value.substring(0, 16);
    }

    // Adiciona os espaços a cada 4 caracteres
    String formatted = '';
    for (int i = 0; i < value.length; i++) {
      if (i > 0 && i % 4 == 0) {
        formatted += ' '; // Adiciona espaço após cada 4 dígitos
      }
      formatted += value[i];
    }

    return formatted;
  }
}

final currencyMask = MaskTextInputFormatter(
  mask: 'R\$ #.###,##',
  filter: {"#": RegExp(r'[0-9]')},
  type: MaskAutoCompletionType.lazy,
);

final percentMask = MaskTextInputFormatter(
  mask: '##.##%',
  filter: {"#": RegExp(r'[0-9]')},
);

final cardMask = MaskTextInputFormatter(
  mask: '#### #### #### ####',
  filter: {"#": RegExp(r'[0-9]')},
  type: MaskAutoCompletionType.lazy,
);

final rgMask = MaskTextInputFormatter(
  mask: '##.###.###-#',
  filter: {"#": RegExp(r'[0-9A-Za-z]')},
);

class RGInputFormatter extends TextInputFormatter {
  @override
  TextEditingValue formatEditUpdate(
      TextEditingValue oldValue,
      TextEditingValue newValue,
      ) {
    String text = newValue.text.replaceAll(RegExp(r'[^\dA-Za-z]'), '');

    if (text.length > 9) {
      text = text.substring(0, 9); // Limit to 9 characters
    }

    String formatted = text;
    if (text.length >= 7) {
      formatted = '${text.substring(0, 2)}.${text.substring(2, 5)}.${text.substring(5, 8)}-${text.substring(8)}';
    } else if (text.length >= 5) {
      formatted = '${text.substring(0, 2)}.${text.substring(2, 5)}.${text.substring(5)}';
    } else if (text.length >= 2) {
      formatted = '${text.substring(0, 2)}.${text.substring(2)}';
    }

    return TextEditingValue(
      text: formatted,
      selection: TextSelection.collapsed(offset: formatted.length),
    );
  }
}
