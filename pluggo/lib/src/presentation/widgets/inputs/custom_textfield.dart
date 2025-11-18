import '../../../presentation/provider/theme_provider.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import '../../../presentation/styles/styles.dart';
import '../../styles/border.dart';
import '../../styles/spacings.dart';
import '../../styles/typography.dart';
/*
Validação Automática: Quando o campo de texto perde o foco (no método _handleFocusChange),
 o método validate do formulário é chamado através do formKey.
  Isso aciona a validação do campo, chamando o validator que você definiu.
   Se houver algum erro no campo, ele será exibido.
 */
class CustomTextField extends StatefulWidget {
  final List<TextInputFormatter>? inputFormatters;
  final TextEditingController? controller;
  final String labelText;
  final String descriptionText;
  final TextInputType? keyboardType;
  final FormFieldValidator<String>? validator;
  final bool? obscureText;
  final ValueChanged<String>? onChanged;
  final TextCapitalization? textCapitalization;
  final TextInputAction? textInputAction;
  final bool? borderEnabled;
  final GlobalKey<FormState> formKey;
  final bool isDark;

  const CustomTextField({
    super.key,
    this.inputFormatters,
    this.controller,
    required this.labelText,
    required this.descriptionText,
    this.keyboardType,
    this.validator,
    this.obscureText = false,
    this.onChanged,
    this.textCapitalization,
    this.textInputAction,
    this.borderEnabled = true,
    this.isDark = false,
    required this.formKey,
  });

  @override
  State<CustomTextField> createState() => _CustomTextFieldState();
}

class _CustomTextFieldState extends State<CustomTextField> {
  late FocusNode _focusNode;
  bool _hasError = false;
  bool _hasValidated = false;
  bool _isObscure = false;

  @override
  void initState() {
    super.initState();
    _isObscure = widget.obscureText!;
    _focusNode = FocusNode();
    _focusNode.addListener(_handleFocusChange);
  }


  void _handleFocusChange() {
    print('Foco mudou: ${_focusNode.hasFocus}');
    if (_focusNode.hasFocus) {
      Scrollable.ensureVisible(
        context,
        alignment: 0.0, // Rola para o topo do campo
        duration: const Duration(milliseconds: 500),
        curve: Curves.easeInOut,
      );
    } else {
      widget.formKey.currentState?.validate();
    }
  }

  @override
  Widget build(BuildContext context) {
    String? prefixImagePath;

    if (!ThemeProvider.of(context).hasInputPrefix) {
      prefixImagePath = null;
    } else if (widget.labelText.toLowerCase().contains('cpf')) {
      prefixImagePath = null;
    }else if (widget.labelText.toLowerCase().contains('rg')) {
      prefixImagePath = null;
    } else if (widget.labelText.toLowerCase().contains('senha')) {
      //prefixImagePath = 'assets/icons/cadeado.png';
      prefixImagePath = null;
    } else if (widget.labelText.toLowerCase().contains('e-mail')) {
      //prefixImagePath = 'assets/img/input_email.png';
      prefixImagePath = null;
    } else if (widget.labelText.toLowerCase().contains('procurar')) {
      //prefixImagePath = 'assets/img/input_search.png';
      prefixImagePath = null;
    } else if (widget.labelText.toLowerCase().contains('telefone') || widget.labelText.toLowerCase().contains('celular')) {
      //prefixImagePath = 'assets/img/input_phone.png';
      prefixImagePath = null;
    } else if (widget.labelText.toLowerCase().contains('cartão')) {
      //prefixImagePath = 'assets/img/input_card.png';
      prefixImagePath = null;
    } else if (widget.labelText.toLowerCase().contains('cvc')) {
      //prefixImagePath = 'assets/img/input_password.png';
      prefixImagePath = null;
    } else if (widget.labelText.toLowerCase().contains('titular')) {
      //prefixImagePath = 'assets/img/input_document.png';
      prefixImagePath = null;
    }

    return Form(
      key: widget.formKey,
      child: Row(children: [
        Expanded(
            child: Container(
              padding: EdgeInsets.only( top: HeightSpacing.extraSmall,),
              alignment: Alignment.bottomCenter,
              child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                /*
                if (ThemeProvider.of(context).hasInputLabel)
                  Text(
                    widget.labelText,
                    style: AppTypography.inputLabel(context),
                  ),

                 */
                SizedBox(height: HeightSpacing.extraSmall / 2),
                Stack(alignment: Alignment.bottomLeft, children: [
                  Container(
                      padding: _hasError ? const EdgeInsets.only(bottom: 2) : const EdgeInsets.only(bottom: 0),
                      child: TextFormField(
                        key: Key(widget.labelText),
                        controller: widget.controller,
                        style: widget.isDark
                            ? AppTypography.newInputTextForDark(context)
                            :AppTypography.inputText(context), //###### muda cor aqui
                        obscureText: _isObscure,
                        inputFormatters: widget.inputFormatters,
                        keyboardType: widget.keyboardType,
                        textCapitalization: widget.textCapitalization ?? TextCapitalization.words,
                        validator: (value) {
                          setState(() {
                            _hasError = value != null && widget.validator != null && widget.validator!(value) != null;
                            _hasValidated = true;
                          });
                          return widget.validator?.call(value);
                        },
                        onChanged: (value) {
                          if (_hasValidated) widget.formKey.currentState?.validate();
                          widget.onChanged?.call(value);
                        },
                        textInputAction: widget.textInputAction ?? TextInputAction.done,
                        focusNode: _focusNode,
                        decoration: InputDecoration(
                          filled: true,
                          //fillColor: ThemeProvider.of(context).themeColors.inputBackground,
                          fillColor: Colors.transparent,
                          errorStyle: AppTypography.inputError(context),
                          errorBorder: AppBorders.inputBorderError(context),
                          focusedErrorBorder: AppBorders.focusedInputBorderError(context),
                          //enabledBorder: widget.borderEnabled == true ? AppBorders.inputBorderGray(context) : AppBorders.inputBorderWhite(context),
                          enabledBorder: widget.isDark
                              ?AppBorders.inputBorderPrimary(context)
                              :AppBorders.inputBorderWhite(context),
                          //focusedBorder: AppBorders.focusedInputBorderGray(context),
                          focusedBorder: widget.isDark
                              ?AppBorders.focusedInputBorderDark(context)
                              :AppBorders.focusedInputBorder(context),
                          errorMaxLines: 2,
                          contentPadding: EdgeInsets.symmetric(vertical: HeightSpacing.small, horizontal: WidthSpacing.medium + 4),
                          suffixIcon: widget.labelText.toLowerCase().contains('senha') // Verifique se labelText é "Senha"
                              ? GestureDetector(
                            child: Transform.translate(
                              offset: const Offset(0, 0),
                              child: Icon(
                                _isObscure ? Icons.visibility_off : Icons.visibility,
                                color: ThemeProvider.of(context).appColors.white,
                                size: HeightSpacing.custom(9) + 9,
                              ),
                            ),
                            onTap: () {
                              setState(() {
                                _isObscure = !_isObscure;
                              });
                            },
                          )
                              : null,
                          prefixIcon: prefixImagePath != null
                              ? Padding(
                            padding: EdgeInsets.only(left: WidthSpacing.smallMedium, right: WidthSpacing.small),
                            child: Image.asset(
                              prefixImagePath,
                              width: HeightSpacing.custom(8) + 8,
                              height: HeightSpacing.custom(8) + 8,
                              color: ThemeProvider.of(context).appColors.white,
                            ),
                          )
                              : null,
                          prefixIconConstraints: BoxConstraints(
                            minWidth: HeightSpacing.custom(16) + 8 + 16,
                            minHeight: HeightSpacing.custom(25),
                          ),
                          labelText: _focusNode.hasFocus
                              ? null
                              : ThemeProvider.of(context).hasInputLabel
                              ? widget.descriptionText
                              : widget.labelText,
                          labelStyle: widget.isDark
                              ?AppTypography.newInputHintForBright(context)
                              : AppTypography.inputHint(context) ,
                          floatingLabelBehavior: FloatingLabelBehavior.never,
                        ),
                      )),
                  /*
                  if (_hasError)
                    Positioned(
                      left: 0,
                      bottom: 0,
                      child: Image.asset(
                        'assets/icons/voltar.png',
                        width: 16,
                        height: 16,
                      ),
                    ),

                   */
                ]),
              ]),
            ))
      ]),
    );
  }
}