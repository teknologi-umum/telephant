protoc --php_out=. protos/telephantmaster.proto --proto_path=./protos &&\
rm -rf ../app/Protos &&\
mkdir -p ../app/Protos &&\
mv ./App/Protos/GPBMetadata ../app/Protos &&\
mv ./App/Protos/* ../app/Protos