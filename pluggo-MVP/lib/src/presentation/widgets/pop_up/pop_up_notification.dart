import 'package:pluggo/src/presentation/provider/theme_provider.dart';
import 'package:pluggo/src/config/routes/app_routes.dart';
import 'package:pluggo/src/presentation/widgets/button/icon_button.dart';
import 'package:pluggo/utils/warning_messages.dart';
import 'package:flutter/material.dart';
import 'package:pluggo/src/presentation/styles/styles.dart';

import '../../styles/decoration.dart';
import '../../styles/spacings.dart';
import '../../styles/typography.dart';

class NotificationPopUp extends StatelessWidget {
  final PopUp popUp;
  final bool isPortrait;
  final bool? success;
  const NotificationPopUp({Key? key, required this.popUp, required this.isPortrait, this.success});

  @override
  Widget build(BuildContext context) {
    print(success);
    Icon getIconBySuccess(BuildContext context) {
      switch (success) {
        case true:
          return Icon(Icons.check_circle_outline, size: 52, color: ThemeProvider.of(context).appColors.success);
        case false:
          return Icon(Icons.cancel_outlined, size: 52, color: ThemeProvider.of(context).appColors.error);
        default:
          return Icon(Icons.info_outline, size: 52, color: ThemeProvider.of(context).themeColors.primaryContainer);
      }
    }

    return GestureDetector(
        onTap: () {
      // Quando o usuário clica fora, retorna o valor desejado.
      Navigator.of(context).pop(success ?? true);
    },
    child: Dialog(
      backgroundColor: ThemeProvider.of(context).themeColors.background,
      child:  Padding(
      padding: EdgeInsets.all(16),
    child: RotatedBox(
        quarterTurns: isPortrait ? 0 : 1,
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.only(
                    topRight: Radius.circular(HeightSpacing.custom(32)),
                    topLeft: Radius.circular(HeightSpacing.custom(32)),
                  ),
                ),
                child: Column(
                children: [
                CircleAvatar(
                radius: 40,
                backgroundColor: ThemeProvider.of(context).themeColors.background,
                child: getIconBySuccess(context),
                ),
                    Container(
                      width: MediaQuery.of(context).size.width,
                      padding: EdgeInsets.only(
                        top: HeightSpacing.small,
                        bottom: HeightSpacing.medium,
                      ),
                      alignment: Alignment.center,
                      child: Text(
                        popUp.title,
                        style: AppTypography.formsSubtitle(context),
                        textAlign: TextAlign.center,
                      ),
                    ),
                ],
                )),

            ClipRRect(
                borderRadius: BorderRadius.only(
                  bottomLeft: Radius.circular(HeightSpacing.custom(32)),
                  bottomRight: Radius.circular(HeightSpacing.custom(32)),
                ),
                child: Container(
                  decoration: AppDecoration.warningDecorationWhite(context).copyWith(color: ThemeProvider.of(context).appColors.primary),
                  padding: EdgeInsets.all(HeightSpacing.small),
                  child: Row(
                    crossAxisAlignment: CrossAxisAlignment.center, // alinha o texto ao topo do ícone
                    children: [
                      CircleAvatar(
                        radius: 20,
                        backgroundColor: Colors.transparent,
                        child: Icon(Icons.info_outline, size: 32, color: ThemeProvider.of(context).themeColors.background),
                      ),
                      SizedBox(width: 8), // espaçamento entre ícone e texto
                      Expanded(
                        child: Text(
                          popUp.description,
                          style: AppTypography.popUpDesc(context),
                        ),
                      ),
                    ],
                  ),
                ),
            ),
          ],
        ),
      ),
      )
    )
    );
  }
}