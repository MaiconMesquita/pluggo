import 'package:flutter/material.dart';
import 'package:pluggo/src/config/routes/app_routes.dart';
import 'package:pluggo/src/config/routes/app_routes.gr.dart';
import 'package:pluggo/src/infra/dto/product_dto.dart';
import 'package:pluggo/src/presentation/provider/main_provider.dart';
import 'package:pluggo/src/provider/style/home_provider.dart';
import 'package:provider/provider.dart';
import '../../../domain/value_object/date.dart';
import '../../../infra/dto/product_global_dto.dart';
import '../../../infra/dto/users_dto.dart';
import '../../controller/scroll_position_controller.dart';
import '../../styles/spacings.dart';
import '../../styles/typography.dart';

class UsersListWidget extends StatelessWidget {
  const UsersListWidget({super.key});

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<UsersProvider>(context, listen: true);
    final mainProvider = Provider.of<MainProvider>(context, listen: true);

    final ScrollPositionController scrollController = ScrollPositionController();

    return Column(
      children: [
        Expanded(
          child: provider.users.isEmpty
              ? Center(
            child: Text(
              'Nenhuma compra efetuada...',
              style: AppTypography.headlineWhite(context),
            ),
          )
              : Scrollbar(
            trackVisibility: true,
            thumbVisibility: true,
            thickness: WidthSpacing.custom(8),
            radius: Radius.circular(WidthSpacing.custom(8)),
            interactive: true,
            controller: scrollController.scrollController,
            child: ListView.builder(
              controller: scrollController.scrollController,
              itemCount: provider.users.length,
              itemBuilder: (context, index) {
                final user = provider.users[index];

                return Column(
                  mainAxisAlignment: MainAxisAlignment.start,
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    GestureDetector(
                      onTap: () async => {
                        //await mainProvider.detailedProduct(user.userId),
                        //appRouter.push(ProductDetailsRoute())
                      },
                      child: Container(
                        padding: EdgeInsets.symmetric(vertical: HeightSpacing.small),
                        child: SizedBox(
                          height: HeightSpacing.custom(60) + 32,
                          child: Row(
                            children: [
                              SizedBox(
                                width: HeightSpacing.custom(25) + 25,
                                height: HeightSpacing.custom(25) + 25,
                                child: ElevatedButton(
                                  onPressed: () async {
                                    print('aquiiiii');
                                    await mainProvider.followUser(user.userId);
                                  },
                                  style: ElevatedButton.styleFrom(
                                    padding: EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                                  ),
                                  child: Icon(
                                    Icons.add,
                                    color: Colors.blue, // ajuste conforme o tema da sua aplicação
                                  ),
                                ),
                              ),
                              SizedBox(width: WidthSpacing.smallMedium),

                              SizedBox(width: WidthSpacing.extraSmall),
                              Column(
                                mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    user.username,
                                    style: AppTypography.bodyTitleBlack(context),
                                  ),
                                  Row(
                                      children: [
                                        Text(
                                          'Seguir',
                                          style: AppTypography.infoItemSmall(context),
                                        ),

                                      ]
                                  ),
                                ],
                              ),
                              SizedBox(width: WidthSpacing.medium),
                            ],
                          ),
                        ),
                      ),
                    ),
                  ],
                );
              },
              padding: EdgeInsets.only(bottom: HeightSpacing.medium, right: HeightSpacing.medium),
            ),
          ),
        ),
      ],
    );
  }
}
