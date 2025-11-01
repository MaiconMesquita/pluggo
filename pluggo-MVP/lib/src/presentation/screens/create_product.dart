import 'package:auto_route/auto_route.dart';
import 'package:brasil_fields/brasil_fields.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:pluggo/src/presentation/provider/main_provider.dart';
import 'package:provider/provider.dart';

import '../../../utils/validators/validate_input.dart';
import '../../domain/enum/color_type.dart';
import '../../domain/value_object/value.dart';
import '../layout/layout_default1.dart';
import '../styles/spacings.dart';
import '../styles/typography.dart';
import '../widgets/appbar/header_default.dart';
import '../widgets/button/custom_loading_button.dart';
import '../widgets/inputs/custom_textfield.dart';

@RoutePage()
class CreateProductScreen extends StatelessWidget {

  @override
  Widget build(BuildContext context) {
    return  _CreatProductWidget();
  }
}
class _CreatProductWidget extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final formKeys = List.generate(4, (index) => GlobalKey<FormState>());
    void next(bool reply)  {
      if (reply) {
        context.router.popUntilRoot();
      }
    }
    return Consumer<MainProvider>(
      builder: (context, provider, child) {

        return DefaultLayout1(
            appBar: const HeaderDefault(
              closeButton: false,
              title: "Adicionar produtos",
            ),
            isBackgroundDark: true,
            child: Column(
              children: [
                SizedBox(height: HeightSpacing.large),
                Text('Informe os campos para adicionar.',
                    style: AppTypography.headlineWhite(context)),
                SizedBox(height: HeightSpacing.mediumLarge),
                CustomTextField(
                  formKey: formKeys[0],
                  keyboardType: TextInputType.text,
                  controller: provider.nameController,
                  inputFormatters: [
                  ],
                  labelText: 'Nome do produto',
                  descriptionText: 'Nome',
                ),

                CustomTextField(
                  formKey: formKeys[1],
                  keyboardType: TextInputType.number,
                  validator: (charge) {
                    return ValidateInput.chargeValue(charge);
                  },
                  controller: provider.productValue,
                  inputFormatters: [
                    FilteringTextInputFormatter.digitsOnly,
                    LengthLimitingTextInputFormatter(100),
                    TextInputFormatter.withFunction((oldValue, newValue) {
                      // Cria uma instância de Value para formatação
                      final value = Value(newValue.text);
                      return TextEditingValue(
                        text: value.valueRealWithCents, // Exibe valor formatado com centavos
                        selection: TextSelection.collapsed(offset: value.valueRealWithCents.length),
                      );
                    }),
                  ],
                  labelText: 'Valor',
                  descriptionText: 'Valor',
                ),

                SizedBox(height: HeightSpacing.medium),
                CustomTextField(
                  formKey: formKeys[2],
                  keyboardType: TextInputType.number,
                  validator: (desc) {
                    return ValidateInput.valueNotEmptyAndZero(desc);
                  },
                  controller: provider.estimateController,
                  inputFormatters: [
                    LengthLimitingTextInputFormatter(100)
                  ],
                  labelText: 'Estimativa de uso',
                  descriptionText: 'Estimativa',
                ),
                SizedBox(height: HeightSpacing.medium),
                CustomTextField(
                  formKey: formKeys[3],
                  keyboardType: TextInputType.datetime,
                  controller: provider.lastPurchaseController,
                  inputFormatters: [
                    FilteringTextInputFormatter.digitsOnly,
                    LengthLimitingTextInputFormatter(8), // Apenas 8 dígitos
                    DataInputFormatter()                  ],
                  labelText: 'Última compra',
                  descriptionText: 'Compra',
                ),
                const Spacer(),
                CustomRoundedLoadingButton(
                    controller: provider.btnController,
                    color: ColorType.secondary,
                    isValid: () =>
                        formKeys.map((formKey) =>
                            formKey.currentState!.validate()).reduce((v,
                            e) => v && e),
                    onPressedCallback: () async
                    {
                     await provider.getCreateProduct();
                    },
                    text: 'Gerar produto'),
              ],
            ));
      },
    );
  }
}
