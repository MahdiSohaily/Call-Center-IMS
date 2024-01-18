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

            # Select records from qtybank where brand is different in qtybankOld
            query = """
                SELECT new.id, b1.name as newBrand, b2.name as oldBrand, new.qty, new.invoice_number
                FROM qtybank new
                JOIN qtybankOld old ON new.id = old.id
                JOIN brand b1 ON new.brand = b1.id
                JOIN brand b2 ON old.brand = b2.id
                WHERE new.brand != old.brand
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
        print(record)
else:
    print("No changed records found.")
