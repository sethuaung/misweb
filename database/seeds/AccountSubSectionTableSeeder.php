<?php

use Illuminate\Database\Seeder;

class AccountSubSectionTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('account_sub_section')->truncate();
        
        \DB::table('account_sub_section')->insert(array (
            0 => 
            array (
                'id' => 1,
                'section_id' => 12,
            'title' => 'Accounts receivable (A/R)',
            'description' => 'Accounts receivable (also called A/R, Debtors, or Trade and other receivables) tracks money that customers owe you for products or services, and payments customers make.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'section_id' => 14,
                'title' => 'Allowance for bad debts',
                'description' => 'Use Allowance for bad debts to estimate the part of Accounts receivable you think you might not collect.
Use this only if you are keeping your books on the accrual basis.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'section_id' => 14,
                'title' => 'Development costs',
                'description' => 'Use Development costs to track amounts you deposit or set aside to arrange for financing, such as an SBA loan, or for deposits in anticipation of the purchase of property or other assets.
When the deposit is refunded, or the purchase takes place, remove the amount from this account.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'section_id' => 14,
                'title' => 'Employee cash advances',
                'description' => 'Use Employee cash advances to track employee wages and salary you issue to an employee early, or other non-salary money given to employees.
If you make a loan to an employee, use the Other current asset account type called Loans to others, instead.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'section_id' => 14,
                'title' => 'Inventory',
                'description' => 'Use Inventory to track the cost of goods your business purchases for resale.
When the goods are sold, assign the sale to a Cost of goods sold account.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'section_id' => 14,
                'title' => 'Investments - Mortgage/real estate loans',
                'description' => 'Use Investments - Mortgage/real estate loans to show the balances of any mortgage or real estate loans your business has made or purchased.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'section_id' => 14,
                'title' => 'Investments - Tax-exempt securities',
                'description' => 'Use Investments - Tax-exempt securities for investments in state and local bonds, or mutual funds that invest in state and local bonds.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'section_id' => 14,
                'title' => 'Investments - Other',
                'description' => 'Use Investments - Other to track the value of investments not covered by other investment account types. Examples include publicly-traded stocks, coins, or gold.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'section_id' => 14,
                'title' => 'Loans to officers',
                'description' => 'If you operate your business as a Corporation or S Corporation, use Loans to officers to track money loaned to officers of your business.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'section_id' => 14,
                'title' => 'Loans to others',
                'description' => 'Use Loans to others to track money your business loans to other people or businesses.
This type of account is also referred to as Notes Receivable.

For early salary payments to employees, use Employee cash advances, instead.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'section_id' => 14,
                'title' => 'Loans to stockholders',
                'description' => 'If you operate your business as a Corporation or S Corporation, use Loans to stockholders to track money your business loans to its stockholders.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'section_id' => 14,
                'title' => 'Other current assets',
                'description' => 'Use Other current assets for current assets not covered by the other types. Current assets are likely to be converted to cash or used up in a year.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'section_id' => 14,
                'title' => 'Prepaid expenses',
                'description' => 'Use Prepaid expenses to track payments for expenses that you won’t recognize until your next accounting period.
When you recognize the expense, make a journal entry to transfer money from this account to the expense account.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'section_id' => 14,
                'title' => 'Retainage',
                'description' => 'Use Retainage if your customers regularly hold back a portion of a contract amount until you have completed a project.
This type of account is often used in the construction industry, and only if you record income on an accrual basis.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'section_id' => 14,
                'title' => 'Undeposited funds',
                'description' => 'Use Undeposited funds for cash or checks from sales that haven’t been deposited yet.
For petty cash, use Cash on hand, instead.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'section_id' => 10,
                'title' => 'Cash on hand',
                'description' => 'Use a Cash on hand account to track cash your company keeps for occasional expenses, also called petty cash.
To track cash from sales that have not been deposited yet, use a pre-created account called Undeposited funds, instead.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'section_id' => 10,
                'title' => 'Checking',
                'description' => 'Use Checking accounts to track all your checking activity, including debit card transactions.
Each checking account your company has at a bank or other financial institution should have its own Checking type account in QuickBooks Online Simple Start.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'section_id' => 10,
                'title' => 'Money market',
                'description' => 'Use Money market to track amounts in money market accounts.
For investments, see Other Current Assets, instead.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'section_id' => 10,
                'title' => 'Rents held in trust',
                'description' => 'Use Rents held in trust to track deposits and rent held on behalf of the property owners.
Typically only property managers use this type of account.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'section_id' => 10,
                'title' => 'Savings',
                'description' => 'Use Savings accounts to track your savings and CD activity.
Each savings account your company has at a bank or other financial institution should have its own Savings type account.

For investments, see Other Current Assets, instead.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'section_id' => 10,
                'title' => 'Trust accounts',
                'description' => 'Use Trust accounts for money held by you for the benefit of someone else.
For example, trust accounts are often used by attorneys to keep track of expense money their customers have given them.

Often, to keep the amount in a trust account from looking like it’s yours, the amount is offset in a contra liability account (a Current Liability).',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'section_id' => 16,
                'title' => 'Accumulated amortization',
                'description' => 'Use Accumulated amortization to track how much you amortize intangible assets.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'section_id' => 16,
                'title' => 'Accumulated depletion',
                'description' => 'Use Accumulated depletion to track how much you deplete a natural resource.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'section_id' => 16,
                'title' => 'Accumulated depreciation',
            'description' => 'Use Accumulated depreciation to track how much you depreciate a fixed asset (a physical asset you do not expect to convert to cash during one year of normal operations).',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'section_id' => 16,
                'title' => 'Buildings',
                'description' => 'Use Buildings to track the cost of structures you own and use for your business. If you have a business in your home, consult your accountant or IRS Publication 587.
Use a Land account for the land portion of any real property you own, splitting the cost of the property between land and building in a logical method. A common method is to mimic the land-to-building ratio on the property tax statement.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
                'section_id' => 16,
                'title' => 'Depletable assets',
                'description' => 'Use Depletable assets to track natural resources, such as timberlands, oil wells, and mineral deposits.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
                'section_id' => 16,
                'title' => 'Fixed Asset Computers',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
                'section_id' => 16,
                'title' => 'Fixed Asset Copiers',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            28 => 
            array (
                'id' => 29,
                'section_id' => 16,
                'title' => 'Fixed Asset Furniture',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            29 => 
            array (
                'id' => 30,
                'section_id' => 16,
                'title' => 'Fixed Asset Other Tools Equipment',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            30 => 
            array (
                'id' => 31,
                'section_id' => 16,
                'title' => 'Fixed Asset Phone',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            31 => 
            array (
                'id' => 32,
                'section_id' => 16,
                'title' => 'Fixed Asset Photo Video',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            32 => 
            array (
                'id' => 33,
                'section_id' => 16,
                'title' => 'Fixed Asset Software',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            33 => 
            array (
                'id' => 34,
                'section_id' => 16,
                'title' => 'Furniture & fixtures',
                'description' => 'Use Furniture & fixtures to track any furniture and fixtures your business owns and uses, like a dental chair or sales booth.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            34 => 
            array (
                'id' => 35,
                'section_id' => 16,
                'title' => 'Intangible assets',
                'description' => 'Use Intangible assets to track intangible assets that you plan to amortize. Examples include franchises, customer lists, copyrights, and patents.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            35 => 
            array (
                'id' => 36,
                'section_id' => 16,
                'title' => 'Land',
                'description' => 'Use Land for land or property you don’t depreciate.
If land and building were acquired together, split the cost between the two in a logical way. One common method is to use the land-to-building ratio on the property tax statement.

For land you use as a natural resource, use a Depletable assets account, instead.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            36 => 
            array (
                'id' => 37,
                'section_id' => 16,
                'title' => 'Leasehold improvements',
                'description' => 'Use Leasehold improvements to track improvements to a leased asset that increases the asset’s value. For example, if you carpet a leased office space and are not reimbursed, that’s a leasehold improvement.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            37 => 
            array (
                'id' => 38,
                'section_id' => 16,
                'title' => 'Machinery & equipment',
                'description' => 'Use Machinery & equipment to track computer hardware, as well as any other non-furniture fixtures or devices owned and used for your business.
This includes equipment that you ride, like tractors and lawn mowers. Cars and trucks, however, should be tracked with Vehicle accounts, instead.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            38 => 
            array (
                'id' => 39,
                'section_id' => 16,
                'title' => 'Other fixed asset',
                'description' => 'Use Other fixed asset for fixed assets that are not covered by other asset types.
Fixed assets are physical property that you use in your business and that you do not expect to convert to cash or be used up during one year of normal operations.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            39 => 
            array (
                'id' => 40,
                'section_id' => 16,
                'title' => 'Vehicles',
                'description' => 'Use Vehicles to track the value of vehicles your business owns and uses for business. This includes off-road vehicles, air planes, helicopters, and boats.
If you use a vehicle for both business and personal use, consult your accountant or the IRS to see how you should track its value.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            40 => 
            array (
                'id' => 41,
                'section_id' => 18,
                'title' => 'Accumulated amortization of other assets',
                'description' => 'Use Accumulated amortization of other assets to track how much you’ve amortized asset whose type is Other Asset.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            41 => 
            array (
                'id' => 42,
                'section_id' => 18,
                'title' => 'Goodwill',
                'description' => 'Use Goodwill only if you have acquired another company. It represents the intangible assets of the acquired company which gave it an advantage, such as favorable government relations, business name, outstanding credit ratings, location, superior management, customer lists, product quality, or good labor relations.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            42 => 
            array (
                'id' => 43,
                'section_id' => 18,
                'title' => 'Lease buyout',
                'description' => 'Use Lease buyout to track lease payments to be applied toward the purchase of a leased asset.
You don’t track the leased asset itself until you purchase it.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            43 => 
            array (
                'id' => 44,
                'section_id' => 18,
                'title' => 'Licenses',
                'description' => 'Use Licenses to track non-professional licenses for permission to engage in an activity, like selling alcohol or radio broadcasting.
For fees associated with professional licenses granted to individuals, use a Legal & professional fees expense account, instead.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            44 => 
            array (
                'id' => 45,
                'section_id' => 18,
                'title' => 'Organizational costs',
                'description' => 'Use Organizational costs to track costs incurred when forming a partnership or corporation.
The costs include the legal and accounting costs necessary to organize the company, facilitate the filings of the legal documents, and other paperwork.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            45 => 
            array (
                'id' => 46,
                'section_id' => 18,
                'title' => 'Other long-term assets',
                'description' => 'Use Other long-term assets to track assets not covered by other types.
Long-term assets are expected to provide value for more than one year',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            46 => 
            array (
                'id' => 47,
                'section_id' => 18,
                'title' => 'Security deposits',
                'description' => 'Use Security deposits to track funds you’ve paid to cover any potential costs incurred by damage, loss, or theft.
The funds should be returned to you at the end of the contract.

If you collect deposits, use an Other current liabilities account type (an Other current liability account).',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            47 => 
            array (
                'id' => 48,
                'section_id' => 20,
                'title' => 'Accounts payable',
            'description' => 'Accounts payable (also called A/P) tracks amounts you owe to your vendors and suppliers.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            48 => 
            array (
                'id' => 49,
                'section_id' => 22,
                'title' => 'Credit card',
                'description' => 'Credit card accounts track the balance due on your business credit cards.
Create one Credit card account for each credit card account your business uses.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            49 => 
            array (
                'id' => 50,
                'section_id' => 24,
                'title' => 'Federal Income Tax Payable',
                'description' => 'Use Federal Income Tax Payable if your business is a corporation, S corporation, or limited partnership keeping records on the accrual basis.
This account tracks income tax liabilities in the year the income is earned.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            50 => 
            array (
                'id' => 51,
                'section_id' => 24,
                'title' => 'Insurance payable',
                'description' => 'Use Insurance payable to keep track of insurance amounts due.
This account is most useful for businesses with monthly recurring insurance expenses such as Workers’ Compensation.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            51 => 
            array (
                'id' => 52,
                'section_id' => 24,
                'title' => 'Line of credit',
                'description' => 'Use Line of credit to track the balance due on any lines of credit your business has. Each line of credit your business has should have its own Line of credit account.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            52 => 
            array (
                'id' => 53,
                'section_id' => 24,
                'title' => 'Loan payable',
                'description' => 'Use Loan payable to track loans your business owes which are payable within the next twelve months.
For longer-term loans, use the Long-term liability called Notes payable, instead.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            53 => 
            array (
                'id' => 54,
                'section_id' => 24,
                'title' => 'Other current liabilities',
                'description' => 'Use Other current liabilities to track liabilities due within the next twelve months that do not fit the Other current liability account types.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            54 => 
            array (
                'id' => 55,
                'section_id' => 24,
                'title' => 'Payroll clearing',
                'description' => 'Use Payroll clearing to keep track of any non-tax amounts that you have deducted from employee paychecks or that you owe as a result of doing payroll. When you forward money to the appropriate vendors, deduct the amount from the balance of this account.
Do not use this account for tax amounts you have withheld or owe from paying employee wages. For those amounts, use the Payroll tax payable account instead.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            55 => 
            array (
                'id' => 56,
                'section_id' => 24,
                'title' => 'Payroll tax payable',
                'description' => 'Use Payroll tax payable to keep track of tax amounts that you owe to Federal, State, and Local government agencies as a result of paying wages and taxes you have withheld from employee paychecks. When you forward the money to the government agency, deduct the amount from the balance of this account.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            56 => 
            array (
                'id' => 57,
                'section_id' => 24,
                'title' => 'Prepaid expenses payable',
                'description' => 'Use Prepaid expenses payable to track items such as property taxes that are due, but not yet deductible as an expense because the period they cover has not yet passed.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            57 => 
            array (
                'id' => 58,
                'section_id' => 24,
                'title' => 'Rents in trust',
                'description' => 'Use Rents in trust - liability to offset the Rents in trust amount in assets.
Amounts in these accounts are held by your business on behalf of others. They do not belong to your business, so should not appear to be yours on your balance sheet. This contra account takes care of that, as long as the two balances match.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            58 => 
            array (
                'id' => 59,
                'section_id' => 24,
                'title' => 'Sales tax payable',
                'description' => 'Use Sales tax payable to track sales tax you have collected, but not yet remitted to the IRS.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            59 => 
            array (
                'id' => 60,
                'section_id' => 24,
                'title' => 'State/local income tax payable',
                'description' => 'Use State/local income tax payable if your business is a corporation, S corporation, or limited partnership keeping records on the accrual basis.
This account tracks income tax liabilities in the year the income is earned.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            60 => 
            array (
                'id' => 61,
                'section_id' => 24,
                'title' => 'Trust accounts - liabilities',
                'description' => 'Use Trust accounts - liabilities to offset Trust accounts in assets.
Amounts in these accounts are held by your business on behalf of others. They do not belong to your business, so should not appear to be yours on your balance sheet. This contra account takes care of that, as long as the two balances match.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            61 => 
            array (
                'id' => 62,
                'section_id' => 24,
                'title' => 'Undistributed Tips',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            62 => 
            array (
                'id' => 63,
                'section_id' => 26,
                'title' => 'Notes payable',
            'description' => 'Use Notes payable to track the amounts your business owes in long-term (over twelve months) loans.
For shorter loans, use the Other current liability account type called Loan payable, instead.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            63 => 
            array (
                'id' => 64,
                'section_id' => 26,
                'title' => 'Other long term liabilities',
                'description' => 'Use Other long term liabilities to track liabilities due in more than twelve months that don’t fit the other Long-term liability account types.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            64 => 
            array (
                'id' => 65,
                'section_id' => 26,
                'title' => 'Shareholder notes payable',
                'description' => 'Use Shareholder notes payable to track long-term loan balances your business owes its shareholders.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            65 => 
            array (
                'id' => 66,
                'section_id' => 30,
                'title' => 'Accumulated Adjustment',
                'description' => 'S corporations use this account to track adjustments to owner’s equity that are not attributable to net income.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            66 => 
            array (
                'id' => 67,
                'section_id' => 30,
                'title' => 'Common stock',
            'description' => 'Corporations use Common stock to track shares of its common stock in the hands of shareholders. The amount in this account should be the stated (or par) value of the stock.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            67 => 
            array (
                'id' => 68,
                'section_id' => 30,
                'title' => 'Estimated Taxes',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            68 => 
            array (
                'id' => 69,
                'section_id' => 30,
                'title' => 'Healthcare',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            69 => 
            array (
                'id' => 70,
                'section_id' => 30,
                'title' => 'Opening Balance Equity',
                'description' => 'System Start creates this account the first time you enter an opening balance for a balance sheet account.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            70 => 
            array (
                'id' => 71,
                'section_id' => 30,
                'title' => 'Owner’s equity',
                'description' => 'S corporations use Owner’s equity to show the cumulative net income or loss of their business as of the beginning of the fiscal year.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            71 => 
            array (
                'id' => 72,
                'section_id' => 30,
                'title' => 'Paid-in capital',
            'description' => 'Corporations use Paid-in capital to track amounts received from shareholders in exchange for stock that are over and above the stock’s stated (or par) value.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            72 => 
            array (
                'id' => 73,
                'section_id' => 30,
                'title' => 'Partner contributions',
                'description' => 'Partnerships use Partner contributions to track amounts partners contribute to the partnership during the year.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            73 => 
            array (
                'id' => 74,
                'section_id' => 30,
                'title' => 'Partner distributions',
                'description' => 'Partnerships use Partner distributions to track amounts distributed by the partnership to its partners during the year.
Don’t use this for regular payments to partners for interest or service. For regular payments, use a Guaranteed payments account (a Expense account in Payroll expenses), instead.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            74 => 
            array (
                'id' => 75,
                'section_id' => 30,
                'title' => 'Partner’s equity',
                'description' => 'Partnerships use Partner’s equity to show the income remaining in the partnership for each partner as of the end of the prior year.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            75 => 
            array (
                'id' => 76,
                'section_id' => 30,
                'title' => 'Personal Expense',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            76 => 
            array (
                'id' => 77,
                'section_id' => 30,
                'title' => 'Personal Income',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            77 => 
            array (
                'id' => 78,
                'section_id' => 30,
                'title' => 'Preferred Stock',
            'description' => 'Corporations use this account to track shares of its preferred stock in the hands of shareholders. The amount in this account should be the stated (or par) value of the stock.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            78 => 
            array (
                'id' => 79,
                'section_id' => 30,
                'title' => 'Retained earnings',
                'description' => 'System Start adds this account when you create your company.
Retained earnings tracks net income from previous fiscal years.

System Start automatically transfers your profit (or loss) to Retained earnings at the end of each fiscal year.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            79 => 
            array (
                'id' => 80,
                'section_id' => 30,
                'title' => 'Treasury stock',
                'description' => 'Corporations use Treasury stock to track amounts paid by the corporation to buy its own stock back from shareholders.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            80 => 
            array (
                'id' => 81,
                'section_id' => 40,
                'title' => 'Discounts/refunds given',
                'description' => 'Use Discounts/refunds given to track discounts you give to customers.
This account typically has a negative balance so it offsets other income.

For discounts from vendors, use an expense account, instead.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            81 => 
            array (
                'id' => 82,
                'section_id' => 40,
                'title' => 'Non-profit income',
                'description' => 'Use Non-profit income to track money coming in if you are a non-profit organization.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            82 => 
            array (
                'id' => 83,
                'section_id' => 40,
                'title' => 'Other primary income',
                'description' => 'Use Other primary income to track income from normal business operations that doesn’t fall into another Income type.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            83 => 
            array (
                'id' => 84,
                'section_id' => 40,
                'title' => 'Sales of product income',
                'description' => 'Use Sales of product income to track income from selling products.
This can include all kinds of products, like crops and livestock, rental fees, performances, and food served.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            84 => 
            array (
                'id' => 85,
                'section_id' => 40,
                'title' => 'Service/fee income',
                'description' => 'Use Service/fee income to track income from services you perform or ordinary usage fees you charge.
For fees customers pay you for late payments or other uncommon situations, use an Other Income account type called Other miscellaneous income, instead.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            85 => 
            array (
                'id' => 86,
                'section_id' => 40,
                'title' => 'Unapplied Cash Payment Income',
                'description' => 'Unapplied Cash Payment Income reports the Cash Basis income from customers payments you’ve received but not applied to invoices or charges. In general, you would never use this directly on a purchase or sale transaction. The IRS calls this Constructive Receipt Income. See Publication 538.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            86 => 
            array (
                'id' => 87,
                'section_id' => 70,
                'title' => 'Dividend income',
                'description' => 'Use Dividend income to track taxable dividends from investments.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            87 => 
            array (
                'id' => 88,
                'section_id' => 70,
                'title' => 'Interest earned',
                'description' => 'Use Interest earned to track interest from bank or savings accounts, investments, or interest payments to you on loans your business made.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            88 => 
            array (
                'id' => 89,
                'section_id' => 70,
                'title' => 'Other investment income',
                'description' => 'Use Other investment income to track other types of investment income that isn’t from dividends or interest.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            89 => 
            array (
                'id' => 90,
                'section_id' => 70,
                'title' => 'Other miscellaneous income',
                'description' => 'Use Other miscellaneous income to track income that isn’t from normal business operations, and doesn’t fall into another Other Income type.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            90 => 
            array (
                'id' => 91,
                'section_id' => 70,
                'title' => 'Tax-exempt interest',
                'description' => 'Use Tax-exempt interest to record interest that isn’t taxable, such as interest on money in tax-exempt retirement accounts, or interest from tax-exempt bonds.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            91 => 
            array (
                'id' => 92,
                'section_id' => 50,
                'title' => 'Cost of labor - COS',
                'description' => 'Use Cost of labor - COS to track the cost of paying employees to produce products or supply services.
It includes all employment costs, including food and transportation, if applicable.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            92 => 
            array (
                'id' => 93,
                'section_id' => 50,
                'title' => 'Equipment rental - COS',
                'description' => 'Use Equipment rental - COS to track the cost of renting equipment to produce products or services.
If you purchase equipment, use a Fixed Asset account type called Machinery and equipment.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            93 => 
            array (
                'id' => 94,
                'section_id' => 50,
                'title' => 'Other costs of service - COS',
                'description' => 'Use Other costs of service - COS to track costs related to services you provide that don’t fall into another Cost of Goods Sold type.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            94 => 
            array (
                'id' => 95,
                'section_id' => 50,
                'title' => 'Shipping, freight & delivery - COGS',
                'description' => 'Use Shipping, freight & delivery - COGS to track the cost of shipping products to customers or distributors.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            95 => 
            array (
                'id' => 96,
                'section_id' => 50,
                'title' => 'Supplies & materials - COGS',
                'description' => 'Use Supplies & materials - COGS to track the cost of raw goods and parts used or consumed when producing a product or providing a service.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            96 => 
            array (
                'id' => 97,
                'section_id' => 60,
                'title' => 'Advertising/promotional',
                'description' => 'Use Advertising/promotional to track money spent promoting your company.
You may want different accounts of this type to track different promotional efforts (Yellow Pages, newspaper, radio, flyers, events, and so on).

If the promotion effort is a meal, use Promotional meals instead.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            97 => 
            array (
                'id' => 98,
                'section_id' => 60,
                'title' => 'Auto',
                'description' => 'Use Auto to track costs associated with vehicles.
You may want different accounts of this type to track gasoline, repairs, and maintenance.

If your business owns a car or truck, you may want to track its value as a Fixed Asset, in addition to tracking its expenses.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            98 => 
            array (
                'id' => 99,
                'section_id' => 60,
                'title' => 'Bad debt',
                'description' => 'Use Bad debt to track debt you have written off.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            99 => 
            array (
                'id' => 100,
                'section_id' => 60,
                'title' => 'Bank charges',
                'description' => 'Use Bank charges for any fees you pay to financial institutions.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            100 => 
            array (
                'id' => 101,
                'section_id' => 60,
                'title' => 'Charitable contributions',
                'description' => 'Use Charitable contributions to track gifts to charity.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            101 => 
            array (
                'id' => 102,
                'section_id' => 60,
                'title' => 'Cost of labor',
                'description' => 'Use Cost of labor to track the cost of paying employees to produce products or supply services.
It includes all employment costs, including food and transportation, if applicable.

This account is also available as a Cost of Goods Sold (COGS) account.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            102 => 
            array (
                'id' => 103,
                'section_id' => 60,
                'title' => 'Dues & subscriptions',
                'description' => 'Use Dues & subscriptions to track dues & subscriptions related to running your business.
You may want different accounts of this type for professional dues, fees for licenses that can’t be transferred, magazines, newspapers, industry publications, or service subscriptions.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            103 => 
            array (
                'id' => 104,
                'section_id' => 60,
                'title' => 'Entertainment',
                'description' => 'Use Entertainment to track events to entertain employees.
If the event is a meal, use Entertainment meals, instead.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            104 => 
            array (
                'id' => 105,
                'section_id' => 60,
                'title' => 'Entertainment meals',
                'description' => 'Use Entertainment meals to track how much you spend on dining with your employees to promote morale.
If you dine with a customer to promote your business, use a Promotional meals account, instead.

Be sure to include who you ate with and the purpose of the meal when you enter the transaction.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            105 => 
            array (
                'id' => 106,
                'section_id' => 60,
                'title' => 'Equipment rental',
                'description' => 'Use Equipment rental to track the cost of renting equipment to produce products or services.
This account is also available as a Cost of Goods (COGS) account.

If you purchase equipment, use a Fixed Asset account type called Machinery and equipment.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            106 => 
            array (
                'id' => 107,
                'section_id' => 60,
                'title' => 'Finance costs',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            107 => 
            array (
                'id' => 108,
                'section_id' => 60,
                'title' => 'Insurance',
                'description' => 'Use Insurance to track insurance payments.
You may want different accounts of this type for different types of insurance (auto, general liability, and so on).',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            108 => 
            array (
                'id' => 109,
                'section_id' => 60,
                'title' => 'Interest Paid',
                'description' => 'Use Interest paid for all types of interest you pay, including mortgage interest, finance charges on credit cards, or interest on loans.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            109 => 
            array (
                'id' => 110,
                'section_id' => 60,
                'title' => 'Legal & Professional Fees',
                'description' => 'Use Legal & professional fees to track money to pay to professionals to help you run your business.
You may want different accounts of this type for payments to your accountant, lawyer, or other consultants.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            110 => 
            array (
                'id' => 111,
                'section_id' => 60,
                'title' => 'Office/General Administrative Expenses',
                'description' => 'Use Office/general administrative expenses to track all types of general or office-related expenses.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            111 => 
            array (
                'id' => 112,
                'section_id' => 60,
                'title' => 'Other Business Expenses',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            112 => 
            array (
                'id' => 113,
                'section_id' => 60,
                'title' => 'Other Miscellaneous Service Cost',
                'description' => 'Use Other miscellaneous service cost to track costs related to providing services that don’t fall into another Expense type.
This account is also available as a Cost of Goods Sold (COGS) account.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            113 => 
            array (
                'id' => 114,
                'section_id' => 60,
                'title' => 'Payroll Expenses',
                'description' => 'Use Payroll expenses to track payroll expenses. You may want different accounts of this type for things like:
Compensation of officers
Guaranteed payments
Workers compensation
Salaries and wages
Payroll taxes',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            114 => 
            array (
                'id' => 115,
                'section_id' => 60,
                'title' => 'Promotional Meals',
                'description' => 'Use Promotional meals to track how much you spend dining with a customer to promote your business.
Be sure to include who you ate with and the purpose of the meal when you enter the transaction.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            115 => 
            array (
                'id' => 116,
                'section_id' => 60,
                'title' => 'Rent or Lease of Buildings',
                'description' => 'Use Rent or lease of buildings to track rent payments you make.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            116 => 
            array (
                'id' => 117,
                'section_id' => 60,
                'title' => 'Repair & Maintenance',
                'description' => 'Use Repair & maintenance to track any repairs and periodic maintenance fees.
You may want different accounts of this type to track different types repair & maintenance expenses (auto, equipment, landscape, and so on).',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            117 => 
            array (
                'id' => 118,
                'section_id' => 60,
                'title' => 'Shipping, Freight & Delivery',
                'description' => 'Use Shipping, freight & delivery to track the cost of shipping products to customers or distributors.
You might use this type of account for incidental shipping expenses, and the Cost of Goods Sold type of Shipping, freight & delivery account for direct costs.

This account is also available as a Cost of Goods Sold account.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            118 => 
            array (
                'id' => 119,
                'section_id' => 60,
                'title' => 'Supplies & Materials',
                'description' => 'Use Supplies & materials to track the cost of raw goods and parts used or consumed when producing a product or providing a service.
This account is also available as a Cost of Goods Sold (COGS) account.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            119 => 
            array (
                'id' => 120,
                'section_id' => 60,
                'title' => 'Taxes Paid',
                'description' => 'Use Taxes paid to track taxes you pay.
You may want different accounts of this type for payments to different tax agencies (sales tax, state tax, federal tax).',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            120 => 
            array (
                'id' => 121,
                'section_id' => 60,
                'title' => 'Travel',
                'description' => 'Use Travel to track travel costs.
For food you eat while traveling, use Travel meals, instead.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            121 => 
            array (
                'id' => 122,
                'section_id' => 60,
                'title' => 'Travel Meals',
                'description' => 'Use Travel meals to track how much you spend on food while traveling.
If you dine with a customer to promote your business, use a Promotional meals account, instead.

If you dine with your employees to promote morale, use Entertainment meals, instead.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            122 => 
            array (
                'id' => 123,
                'section_id' => 60,
                'title' => 'Unapplied Cash Bill Payment Expense',
                'description' => 'Unapplied Cash Bill Payment Expense reports the Cash Basis expense from vendor payment checks you’ve sent but not yet applied to vendor bills. In general, you would never use this directly on a purchase or sale transaction. See IRS Publication 538.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            123 => 
            array (
                'id' => 124,
                'section_id' => 60,
                'title' => 'Utilities',
                'description' => 'Use Utilities to track utility payments.
You may want different accounts of this type to track different types of utility payments (gas and electric, telephone, water, and so on).',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            124 => 
            array (
                'id' => 125,
                'section_id' => 80,
                'title' => 'Amortization',
                'description' => 'Use Amortization to track amortization of intangible assets.
Amortization is spreading the cost of an intangible asset over its useful life, like depreciation of fixed assets.

You may want an amortization account for each intangible asset you have.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            125 => 
            array (
                'id' => 126,
                'section_id' => 80,
                'title' => 'Depreciation',
                'description' => 'Use Depreciation to track how much you depreciate fixed assets.
You may want a depreciation account for each fixed asset you have.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            126 => 
            array (
                'id' => 127,
                'section_id' => 80,
                'title' => 'Exchange Gain or Loss',
                'description' => 'Use Exchange Gain or Loss to track gains or losses that occur as a result of exchange rate fluctuations.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            127 => 
            array (
                'id' => 128,
                'section_id' => 80,
                'title' => 'Gas And Fuel',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            128 => 
            array (
                'id' => 129,
                'section_id' => 80,
                'title' => 'Home Office',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            129 => 
            array (
                'id' => 130,
                'section_id' => 80,
                'title' => 'Homeowner Rental Insurance',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            130 => 
            array (
                'id' => 131,
                'section_id' => 80,
                'title' => 'Mortgage Interest Home Office',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            131 => 
            array (
                'id' => 132,
                'section_id' => 80,
                'title' => 'Other Home Office Expenses',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            132 => 
            array (
                'id' => 133,
                'section_id' => 80,
                'title' => 'Other Miscellaneous Expense',
                'description' => 'Use Other miscellaneous expense to track unusual or infrequent expenses that don’t fall into another Other Expense type.
If an expense is directly related to providing a service, use an Expense type (not an Other Expense type) account called Other miscellaneous service cost.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            133 => 
            array (
                'id' => 134,
                'section_id' => 80,
                'title' => 'Other Vehicle Expenses',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            134 => 
            array (
                'id' => 135,
                'section_id' => 80,
                'title' => 'Parking and Tolls',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            135 => 
            array (
                'id' => 136,
                'section_id' => 80,
                'title' => 'Penalties & Settlements',
                'description' => 'Use Penalties & settlements to track money you pay for violating laws or regulations, settling lawsuits, or other penalties.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            136 => 
            array (
                'id' => 137,
                'section_id' => 80,
                'title' => 'Rent and Lease Home Office',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            137 => 
            array (
                'id' => 138,
                'section_id' => 80,
                'title' => 'Repairs and Maintenance Home Office',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            138 => 
            array (
                'id' => 139,
                'section_id' => 80,
                'title' => 'Utilities Home Office',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            139 => 
            array (
                'id' => 140,
                'section_id' => 80,
                'title' => 'Vehicle',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            140 => 
            array (
                'id' => 141,
                'section_id' => 80,
                'title' => 'Vehicle Insurance',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            141 => 
            array (
                'id' => 142,
                'section_id' => 80,
                'title' => 'Vehicle Lease',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            142 => 
            array (
                'id' => 143,
                'section_id' => 80,
                'title' => 'Vehicle Loan',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            143 => 
            array (
                'id' => 144,
                'section_id' => 80,
                'title' => 'Vehicle Loan Interest',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            144 => 
            array (
                'id' => 145,
                'section_id' => 80,
                'title' => 'Vehicle Registration',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            145 => 
            array (
                'id' => 146,
                'section_id' => 80,
                'title' => 'Vehicle Repairs',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            146 => 
            array (
                'id' => 147,
                'section_id' => 80,
                'title' => 'Wash and Road Services',
                'description' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}