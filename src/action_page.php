require 'vendor/autoload.php';

date_default_timezone_set('UTC');

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

$sdk = new Aws\Sdk([
    'endpoint'   => 'http://localhost:8000',
    'region'   => 'us-east-1',
    'version'  => 'latest'
]);

$dynamodb = $sdk->createDynamoDb();
$marshaler = new Marshaler();

$tableName = 'MovieArchive';

$eav = $marshaler->marshalJson('
    {
        ":yyyy": 1981 
    }
');

$params = [
    'TableName' => $tableName,
    'KeyConditionExpression' => '#Year = :yyyy',
    'ExpressionAttributeNames'=> [ '#Year' => 'Year' ],
    'ExpressionAttributeValues'=> $eav
];

echo "Querying for movies from 1981.\n";

try {
    $result = $dynamodb->query($params);

    echo "Query succeeded.\n";

    foreach ($result['Items'] as $movie) {
        echo $marshaler->unmarshalValue($movie['year']) . ': ' .
            $marshaler->unmarshalValue($movie['title']) . "\n";
    }

} catch (DynamoDbException $e) {
    echo "Unable to query:\n";
    echo $e->getMessage() . "\n";
}