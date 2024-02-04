import mysql.connector

def connect_to_database():
    try:
        # Replace the placeholders with your actual database credentials
        connection = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="yadakshop1402"
        )
        return connection
    except mysql.connector.Error as err:
        print(f"Error: {err}")
        return None

def compare_and_get_changed_records():
    connection = connect_to_database()
    
    if connection is not None:
        try:
            cursor = connection.cursor(dictionary=True)

            # Select records from qtybank where seller is different in oldQtybank and changed to 0
            query = """
                SELECT new.id, new.qty, new.invoice_number, old.seller as oldSeller, new.seller as newSeller
                FROM qtybank new
                JOIN oldQtybank old ON new.id = old.id
                WHERE new.seller != old.seller AND new.seller = 0
            """
            
            cursor.execute(query)
            changed_records = cursor.fetchall()

            return changed_records

        except mysql.connector.Error as err:
            print(f"Error: {err}")

        finally:
            cursor.close()
            connection.close()

    return None

# Example usage
changed_records = compare_and_get_changed_records()

if changed_records:
    for record in changed_records:
        print(f"ID: {record['id']}, Qty: {record['qty']}, Invoice Number: {record['invoice_number']}, Old Seller: {record['oldSeller']}, New Seller: {record['newSeller']}")
else:
    print("No changed records found.")
