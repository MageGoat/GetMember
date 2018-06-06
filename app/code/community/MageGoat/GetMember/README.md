tabela getmember_member
	
	member_id
	customer_id *
	member_code Idx_key unique
	comment
	custom_points
	is_active
	create-at 
	update_at


tabela getmember_point
	
	point_id
	order_increment_id
	customer_member_id *
	member_code - <history>
	points
	states ('available' - 'used')
	create-at 
	update_at

¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨
member_code  - will be save on session 
	<observer controller_action_predispatch>

in saveOrder - will create a record on table Member_Points
	<observer checkout_type_onepage_save_order>
		```customer_id of 'member_code' (get customerId by member_code)```

in Invoice pay - Member_Points with set point value, 
	<obsever sales_order_invoice_pay>
		invoice status == Mage_Sales_Model_Order_Invoice::STATE_PAID
		```using getOrderIncrementId() ```


filter all Member_Points by customer_id where state states is available
	#total points

##Utils
DROP TABLE `getmember_member`;
DROP TABLE `getmember_point`;
DELETE FROM `core_resource` WHERE `core_resource`.`code` = 'getmember_setup';