<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BusinessChinese extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_zh', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('business_id');
            $table->bigInteger('business_broker')->nullable()->comment('买方中介方');
            $table->string('company', 200)->nullable()->comment('company name');
            $table->string('title', 200)->nullable()->comment('show for the title');
            $table->string('listing', 100)->nullable()->comment('listing ex LA04551');
            $table->string('industry', 200)->nullable()->comment('');
            $table->tinyInteger('type')->nullable()->comment('business type 1 company;2 chain,3 hotel,4 store,5 other');
            $table->double('price', 10, 2)->nullable()->comment('business price');
            $table->tinyInteger('employee_count')->nullable()->comment('business price');
            //location
            $table->string('country', 100)->nullable();
            $table->string('states', 150)->nullable();
            $table->string('city', 150)->nullable();
            $table->string('address', 200)->nullable();


            $table->tinyInteger('profitability')->nullable()->comment('是否盈利');
            $table->tinyInteger('real_estate')->nullable()->comment('是否包含房地产');
            $table->Integer('building_sf')->nullable()->comment('营业面积');

            /**
             * 第二批文件列表
             */
            $table->double('gross_income')->nullable()->comment('毛利润 EX. $8300/month');
            $table->double('value_of_real_estate')->nullable()->comment('Est. Value of Real Estate房地产估价');

            $table->double('net_income')->nullable()->comment('Net Income 净利润 $25,000/month');
            $table->double('lease')->nullable()->comment('租金 $25,000/month');
            $table->timestamp('lease_term')->nullable()->comment('Lease Term 租约有效期');


            $table->text('ebitda')->nullable()->comment('EBITDA(Earning Before Interest, Tax, Depreciation & Amortization)
税息折旧及摊销前利润');
            $table->text('ff_e')->nullable()->comment('FF&E(Furniture, Fixture, & Equipment)硬件资产价值');

            $table->string('inventory', 100)->nullable()->comment('Inventory库存');

            $table->float('commission')->nullable()->comment('Commission佣金 EX. 2.5%');

            $table->string('buyer_financing', 255)->nullable()->comment('Buyer Financing卖家融资');

            /**
             * level three
             */
            $table->string('us_broker', 255)->nullable()->comment('US Broker美国中介');
            $table->string('us_broker_contact_info', 255)->nullable()->comment('US Broker Contact Info美国中介联系方式');
            $table->string('listing_date', 255)->nullable()->comment('Listing Date待售开始日期');

            $table->text('reason_for_selling')->nullable()->comment('Reason for Selling出售原因');

            $table->text('business_description')->nullable()->comment('business description');
            $table->text('business_assets')->nullable()->comment('business description');
            $table->text('financial_performance')->nullable()->comment('business description');

            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
