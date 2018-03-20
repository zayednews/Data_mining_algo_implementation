#include<iostream>
#include<fstream>
#include<math.h>
#define m 150
#define n 4
using namespace std;
int main()
{
    int i,j,k,flag=1,count=0,minGroup,counter;
    int cluster[m]={0},finalCluster[m],result[200];
    float data[m][n];
    float distance,eps;
    fstream ifs;
    ifs.open("iris4.txt");

    for(i=0;i<m;i++)
    {
        for(j=0;j<n;j++)
        {
            ifs>>data[i][j];
        }
    }
    for(i=0;i<m;i++)
    {
        for(j=0;j<n;j++)
        {
            cout<<data[i][j]<<"     ";
        }
        cout<<endl;
    }
    cin>>eps>>minGroup;
    //cluster[0][0]=0;
    //cluster[0][1]=1;
    //for(int i=0;i<2;i++)
        //cout<<cluster[0][i]<<"     ";
    cout<<endl;

    for(i=0;i<m;i++)
    {
        if(cluster[i]>=0)
        {
            cout<<"dhukse "<<i<<" ";
            for(k=0;k<m;k++)
            {
                cout<<k<<endl;
                distance = 0;
                for(j=0;j<n;j++)
                {
                    distance = distance + pow(data[i][j]-data[k][j],2);
                }
                distance = sqrt(distance);
                cout<<distance<<endl;
                if(distance<eps)
                {
                    //cout<<i<<endl;
                    if(cluster[k]==0)
                        cluster[k]=flag;
                }
            }
        }
        flag++;
    }



    for(i=0;i<m;i++)
    {
        counter=0;
        for(j=0;j<m;j++)
        {
            if(cluster[i]==cluster[j])
            {
                counter++;
            }
        }
        finalCluster[i]=counter;
    }

    for(i=0;i<m;i++)
        cout<<i<<" "<<cluster[i]<<" "<<finalCluster[i]<<endl;

    cout<<endl<<endl;


    for(i=0;i<m;i++)
    {
        for(j=0;j<i;j++)
        {
            if(cluster[i] == cluster[j])
                break;
        }
        if(i == j && finalCluster[i]>=minGroup)
        {
            count++;
        }
    }

    cout<<"Total number of clusters = "<<count<<endl;

    return 0;
}
