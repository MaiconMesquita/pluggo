class ValueValidator {
  static bool isValid(String? value, num minLength, num maxLength, {bool clearNonNumber = false}) {
    if (value == null) return false;

    if (clearNonNumber) {
      value = value.replaceAll(RegExp(r'[^0-9]'), '');
    }

    final length = value.length;

    if (value.startsWith('0')) {
      return false;
    }

    return length >= minLength && length <= maxLength;
  }
}