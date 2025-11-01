import 'package:flutter/material.dart';
import 'package:pluggo/src/config/routes/app_routes.dart';
import 'package:pluggo/src/config/routes/app_routes.gr.dart';
import 'package:pluggo/src/infra/dto/product_dto.dart';
import 'package:pluggo/src/presentation/provider/main_provider.dart';
import 'package:pluggo/src/provider/style/home_provider.dart';
import 'package:provider/provider.dart';
import '../../../domain/value_object/date.dart';
import '../../../infra/dto/product_global_dto.dart';
import '../../controller/scroll_position_controller.dart';
import '../../styles/spacings.dart';
import '../../styles/typography.dart';

class GlobalProductListWidget extends StatelessWidget {
  const GlobalProductListWidget({super.key});

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<ProductGlobalProvider>(context, listen: true);
    final mainProvider = Provider.of<MainProvider>(context, listen: true);

    final ScrollPositionController scrollController = ScrollPositionController();

    return Column(
      children: [
        Expanded(
          child: provider.products.isEmpty
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
              itemCount: provider.products.length,
              itemBuilder: (context, index) {
                final product = provider.products[index];

                return Column(
                  mainAxisAlignment: MainAxisAlignment.start,
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    GestureDetector(
                      onTap: () async => {
                        await mainProvider.detailedProduct(product.id),
                        appRouter.push(ProductDetailsRoute())
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
                                    await mainProvider.copyProduct(product.id);
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
                                    product.name,
                                    style: AppTypography.bodyTitleBlack(context),
                                  ),
                                  Row(
                                      children: [
                                        Text(
                                          'Valor: ',
                                          style: AppTypography.infoItemSmall(context),
                                        ),
                                        Text(
                                          product.value.valueRealWithCents,
                                          maxLines: 1,
                                          overflow: TextOverflow.ellipsis,
                                          style: AppTypography.infoItemSmall(context),
                                        ),
                                      ]
                                  ),
                                  Flexible(
                                    child: Row(
                                  children: [
                                Text(
                                'por: ',
                                style: AppTypography.infoItemSmall(context),
                              ),
                                    Text(
                                      product.addedBy,
                                      maxLines: 1,
                                      overflow: TextOverflow.ellipsis,
                                      style: AppTypography.infoItemSmall(context),
                                    ),
                                  ]
                              )
                                  ),
                                  Text(
                                    Date(DateTime.parse(product.reminderDate)).toFull,
                                    style: AppTypography.infoItemSmall(context),
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
