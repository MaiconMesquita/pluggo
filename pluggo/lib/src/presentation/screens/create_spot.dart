import 'package:auto_route/auto_route.dart';
import 'package:brasil_fields/brasil_fields.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:pluggo/src/presentation/provider/main_provider.dart';
import 'package:provider/provider.dart';

import '../../../utils/validators/validate_input.dart';
import '../../domain/entity/device.dart';
import '../../domain/enum/color_type.dart';
import '../../domain/value_object/value.dart';
import '../layout/layout_default1.dart';
import '../provider/maps_provider.dart';
import '../styles/spacings.dart';
import '../styles/typography.dart';
import '../widgets/appbar/header_default.dart';
import '../widgets/button/custom_loading_button.dart';
import '../widgets/inputs/custom_textfield.dart';

@RoutePage()
class CreateSpotScreen extends StatelessWidget {

  @override
  Widget build(BuildContext context) {
    return  _CreatSpottWidget();
  }
}
class _CreatSpottWidget extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final formKeys = List.generate(1, (index) => GlobalKey<FormState>());
    void next(bool reply)  {
      if (reply) {
        context.router.popUntilRoot();
      }
    }
    return Consumer<MapsProvider>(
      builder: (context, provider, child) {

        return DefaultLayout1(
            appBar: const HeaderDefault(
              closeButton: false,
              title: "Adicionar carregador",
            ),
            isBackgroundDark: true,
            child: Column(
              children: [
                SizedBox(height: HeightSpacing.large),
                Text('Informe os campos para adicionar.', style: AppTypography.headlineWhite(context)),
                SizedBox(height: HeightSpacing.mediumLarge),

                // Campo Nome
                CustomTextField(
                  formKey: formKeys[0],
                  keyboardType: TextInputType.text,
                  controller: provider.nameController,
                  labelText: 'Nome do lugar',
                  descriptionText: 'Nome',
                ),
/*
                // Campo Valor
                CustomTextField(
                  formKey: formKeys[1],
                  keyboardType: TextInputType.number,
                  validator: (charge) => ValidateInput.chargeValue(charge),
                  controller: provider.productValue,
                  inputFormatters: [
                    FilteringTextInputFormatter.digitsOnly,
                    LengthLimitingTextInputFormatter(100),
                    TextInputFormatter.withFunction((oldValue, newValue) {
                      final value = Value(newValue.text);
                      return TextEditingValue(
                        text: value.valueRealWithCents,
                        selection: TextSelection.collapsed(offset: value.valueRealWithCents.length),
                      );
                    }),
                  ],
                  labelText: 'Valor por KWH',
                  descriptionText: 'Valor',
                ),

 */

                SizedBox(height: HeightSpacing.mediumLarge),

                // Mapa para selecionar localização
                SizedBox(
                  height: 250, // define altura do mapa
                  child: GoogleMap(
                    initialCameraPosition: CameraPosition(
                      target: LatLng(Device.latitude, Device.longitude),
                      zoom: 14,
                    ),
                    markers: provider.selectedSpot != null
                        ? {
                      Marker(
                        markerId: const MarkerId("selectedSpot"),
                        position: provider.selectedSpot!,
                        icon: BitmapDescriptor.defaultMarkerWithHue(BitmapDescriptor.hueGreen),
                        infoWindow: const InfoWindow(title: "Novo Spot"),
                      ),
                    }
                        : {},
                    onTap: (latLng) {
                      provider.setSelectedSpot(latLng); // função no provider para atualizar
                    },
                  ),
                ),

                const Spacer(),

                // Botão para salvar
                CustomRoundedLoadingButton(
                  controller: provider.btnController,
                  color: ColorType.secondary,
                  isValid: () =>
                      formKeys.map((formKey) => formKey.currentState!.validate()).reduce((v, e) => v && e),
                  onPressedCallback: () async {
                    if (provider.selectedSpot == null) {
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(content: Text("Selecione a localização no mapa")),
                      );
                      return;
                    }

                    await provider.createSpot(); // função que envia nome, valor e localização para backend
                  },
                  text: 'Gerar ponto',
                ),
              ],
            )
        );
      },
    );
  }
}
